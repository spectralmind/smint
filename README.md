# SEARCH by Sound Web Application

## Introduction

SEARCH by Sound is a Web front-end to the SEARCH by Sound Backend Server. It enables efficient search for similar sounding songs that match a given track. The results are ranked by degree of acoustic similarity including parameters that capture tempo, rhythmic feel and genre.

Users can perform both text-based searches (e.g. by artist, title or genre) but also search for music based on providing samples songs or song sequences. The SEARCH by Sound Backend Server takes care of analyzing music in a database and matching acoustic similarity.

SEARCH by Sound Web Application is based on the PHP framework Symfony and published under the MIT license (see LICENSE file in the same directory for the complete terms).

The source code in this repository contains the following modules:
* User registration
* Music player with waveform display
* Metadata search
* Query by (selecting a part of) a music file in the database
* Query by (selecting a part of) an uploaded file

### Spectralmind

Spectralmind was an innovative media technology company founded 2008 by a group of music enthusiasts and semantic audio analysis experts in Vienna, Austria:

Thomas Lidy, Ewald Peiszer, Johann Waldherr and Wolfgang Jochum

Spectralmind�s audio analysis and music discovery applications allow computers to hear music in a similar way as humans do and consequently to find and recommend music by its mere content. This technology is an enabler for solutions in media search, categorization and recommendation.

In addition to the SEARCH by Sound Web Application, Spectralmind also created the SEARCH by Sound music analysis server and music discovery applications for Web, iOS and Android, foremost [Sonarflow] (http://www.sonarflow.com) (also see below).

Spectralmind ceased operations as of September 2015 and published its software stack as open source software under the MIT license.

### Available software

Spectralmind's open source software is comprised of four repositories:

* [SEARCH by Sound Platform a.k.a. Smafe](https://www.github.com/spectralmind/smafe)
* [SEARCH by Sound Web Application a.k.a. Smint](https://www.github.com/spectralmind/smint)
* [Sonarflow iOS App](https://www.github.com/spectralmind/sonarflow-ios)
* [Sonarflow Android App](https://www.github.com/spectralmind/sonarflow-android)

### Resources

The following resources are available:

* `README` - this file
* `INSTALL` - information on building and deploying Smint
* `Search by Sound Screenshot.png` - screenshot
* `doc/Search by Sound Quickstart Guide` - screen by screen walkthrough (not up to date)

### Demo

A demo installation for testing the software is currently available at:

http://musicbricks.ifs.tuwien.ac.at/smintfma/

A user registration is *not required*.

## Build process

Most documentation refers to installing Smint for the Apache web server.

Please refer to INSTALL file in the same directory for concrete instructions and a list of dependencies.

## Support

As Spectralmind ceased operation, no support can be given by the company. Please contact any active members on github, or otherwise you can still try technology@spectralmind.com .

## Acknowledgement

We wish to thank all the contributors to this software, with a special thank you to all former employees and freelancers of Spectralmind.

September 2015
The Founders of Spectralmind: Thomas Lidy, Ewald Peiszer, Johann Waldherr 
