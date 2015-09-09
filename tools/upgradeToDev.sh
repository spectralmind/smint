#!/bin/bash 
# online resources:
# http://steve-parker.org/sh/sh.shtml
# http://www.freeos.com/guides/lsst/
# http://www.ss64.com/bash/

echo -e "--------------------------------------------- "
echo -e "This script will checkout SMINT to the folder \"smintCurrentDev\" and then copy the contents to a given \"target folder\"."
echo -e " "
echo -e " hetzner:smint/smint -> target folder."
echo -e " "

echo " call with parameter (need to be in exact order): "
echo "---"
echo " sudo ./upgradeToDev.sh [target folder] force [username]"
echo "---"
echo "  target folder - folder that will be updated"
echo "  force         - force will overwrite the folder /smintCurrentDev if it exists "  
echo "  username      - username that is used to access the git repository"

echo " "
echo "The following files will be preserved (if they exist): "
echo -e " web/.htaccess \n apps/smint/config/app.yml \n config/databases.yml \n log/* \n /web/uploads/mp3uploads/ \n /log/ \n"

echo -e "The folder \"smintCurrentDev\" will be deleted. If force is used.\n"
echo -e "Existing smint version will be saved in folder - /archive -.\n"

echo "--------------------------------------------- "
echo ""


# if the user is not root exit 
if [ `whoami` != 'root' ]; then
  echo -e "\n--------------------------------------------- "
	echo -e " You have to run the script as root user !!! use sudo !!!! "
  echo -e "--------------------------------------------- "
  exit
fi

# if less than 1 argument is provided, display info
if [ $# -lt 1 ]; then
  echo -e "\n Missing parameter: "
  echo -e "--------------------------------------------- \n"
  echo -e "Please provide target folder as parameter. \n"
  echo -e "--------------------------------------------- \n"
  exit
fi

# argument 3 exists -> use it as username
if [ $# -ge 3 ]; then
  GITUSER=$3
else
  GITUSER=gitrepos  
fi
# echo -e "\n - using GITUSER - $GITUSER"

if [ -d smintCurrentDev ] ; then 
  if [ $# -lt 2 ] || [[ $2 != 'force' ]]; then
    echo -e "\n--------------------------------------------- "
    echo -e " /smintCurrentDev - exists. if your are sure to delete it during upgrade use option \"force\" as second parameter.\n"
    echo -e "--------------------------------------------- \n"
    exit
  fi
fi  


rm -rf smintCurrentDev
echo -e "\nFolder \"smintCurrentDev\" deleted."

#get latest dev version
echo -e "\nGetting latest Dev Version: "
echo -e "\n--------------------------------------------- "
echo -e "- enter password for GITUSER : $GITUSER "
echo -e "--------------------------------------------- \n"

git clone -q $GITUSER@dev.spectralmind.com:smint/smint smintCurrentDev
#cd smintCurrentDev;sudo git checkout -t origin/NAB2012demo;cd ..  #checkout branch
#git clone ~gitrepos/smint.git smintCurrentDev              #local checkout (without user)

if [ ! -d smintCurrentDev ] ; then 
  echo -e "\n--------------------------------------------- "
  echo -e " /smintCurrentDev - does not exists. check if checkout works.\n"
  echo -e "--------------------------------------------- \n"
  exit
fi  
#cd smintCurrentDev
# git checkout origin/newsmint
#cd ..


echo -e "\nUpdating folder: $1"
folder=$1

if [ -d $1 ] ; then 
  echo "- Creating copy of existing folder: $1"
  # copy to temp
  foldertmp=${folder%/}tmpsave/
  mv $folder/ $foldertmp
else
  echo "- Target folder does not exist skipping backup of folder: $1"  
fi

echo "- Moving dev version to folder: $1"
# copy dev to folder
mv smintCurrentDev/ $folder/

echo -e "\nLooking for configuration schema changes: "
if [[ -e $foldertmp/apps/smint/config/app.yml ]]; then
  echo "- Comparing app.yml config file for changes: "
  # compare config files
  ORILINES=$(cat $foldertmp/apps/smint/config/app.yml | wc -l)
  NEWLINES=$(cat $folder/apps/smint/config/app.yml | wc -l)
  if [ $ORILINES -ne $NEWLINES ]; then
    echo -e "\n --------------------------------------------- \n"
    echo -e "The number of lines in the config files differ! $ORILINES vs. $NEWLINES \n"
    echo -e "If there are new config parameters please add those lines to the file: $folder/apps/smint/config/app.yml \n"
    echo -e "The differences are the following ( new file - original file ) : \n"
    echo -e "               | modified  "
    echo -e "               > deleted  "
    echo -e "               < added  "
    echo -e "\n --------------------------------------------- \n"

    diff $folder/apps/smint/config/app.yml $foldertmp/apps/smint/config/app.yml --side-by-side | grep -E '<|>|\|'

    echo -e "\n --------------------------------------------- \n"
  fi
else
  echo "- No app.yml found skipping schema change check "
fi


echo -e "\nRestoring config files"
# restore config files
if [[ -e $foldertmp/web/.htaccess ]]; then
  echo "- Restoring web/.htaccess"
  cp $foldertmp/web/.htaccess $folder/web/.htaccess
fi

if [[ -e $foldertmp/apps/smint/config/app.yml ]]; then
  echo "- Restoring config/app.yml"
  cp $foldertmp/apps/smint/config/app.yml $folder/apps/smint/config/app.yml
fi

if [[ -e $foldertmp/config/databases.yml ]]; then
  echo "- Restoring config/databases.yml "
  cp $foldertmp/config/databases.yml $folder/config/databases.yml 
fi

#move uploaded files 
if [[ -d $foldertmp/web/uploads/mp3uploads ]] && [ "$(ls $foldertmp/web/uploads/mp3uploads/)" ]; then
  echo "- Restoring uploaded files "
  mv $foldertmp/web/uploads/mp3uploads/* $folder/web/uploads/mp3uploads/
fi

# restore log files
if [[ -d $foldertmp/log ]] && [ "$(ls $foldertmp/log/)" ]; then
  echo -e "- Restoring log files"
  cp -r $foldertmp/log/* $folder/log/
fi

#cp $foldertmp/config/preparedtest.csv $folder/config/preparedtest.csv

if [[ -d $foldertmp ]]; then
  echo -e "\nMoving folder to archive \n"
  #move old folder to archive
  mkdir -p archive
  mv $foldertmp archive/`date "+%y%m%d_%H%M%S_"`$foldertmp
fi

echo -e "\nSet user 4 apache \n"
# set user 4 apache
chown www-data:www-data -R $folder



echo -e "\nClear symfony cache \n"
# clear symfony cache 
cd $folder
./symfony cc
cd ..

