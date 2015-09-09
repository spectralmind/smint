/*
smint helper functions and objects
wolfgang jochum 2010/2011
*/

// function to supress enter in form fields
  function suppressEnter(e)
  {
    e=e?e:window.event;
    if(e.keyCode == 13){ 
      return false 
    }
    else{
      return true 
    }
  }

// used to send feedback to backend
  function sendFeedbackText(event)
  {
    eval('send_'+event.target.id+'()');
  }

//object to wait until submit is executed for live search

// live delayFunctionObj 
// set timeout and clears it everytime the call is repeated before timeout 
  function delayFunctionObj(timeout) {
    var timeout; // in seconds
    //_delayFunctionObjRef = this; 
    var delayFunctionObjTimer; 
    var functionstring; 
    
    this.timeout=(typeof(timeout) == 'undefined') ? 1000 : timeout ;
    
    
//public methods    
    this.delayedFunctionCallWithTimeoutUpdate = delayedFunctionCallWithTimeoutUpdate; 
    
    function delayedFunctionCallWithTimeoutUpdate(functionstring) {
      this.functionstring = functionstring; 
      clearTimeout(delayFunctionObjTimer); 
//      delayFunctionObjTimer = setTimeout ( _delayFunctionObjRef.functionstring , _delayFunctionObjRef.timeout );      
      delayFunctionObjTimer = setTimeout ( this.functionstring , this.timeout );      

    }
  }




// object to hold the current related query infos
  function relatedQueryObj(uploadedFilename,originalFilename,metadataquery,isFileUploaded,queryTrackId, segmentStart, segmentEnd, duration) {
    
    var uploadedFilename;
    var originalFilename;
    var metadataquery;
    var isFileUploaded;
    var queryTrackId;
    var segmentStart;
    var segmentEnd;
    var duration;
    var isSegmented;

    this.uploadedFilename=(typeof(uploadedFilename) == 'undefined') ? '' : uploadedFilename ;
    this.originalFilename=(typeof(originalFilename) == 'undefined') ? '' : originalFilename ;
    this.metadataquery=(typeof(metadataquery) == 'undefined') ? '' : jQuery.trim(metadataquery);  
    this.isFileUploaded=(typeof(isFileUploaded) == 'undefined') ? false : isFileUploaded ;
    this.queryTrackId=(typeof(queryTrackId) == 'undefined') ? '' : queryTrackId ;
    this.segmentStart=(typeof(segmentStart) == 'undefined') ? 0 : segmentStart ;
    this.segmentEnd=(typeof(segmentEnd) == 'undefined') ? 0 : segmentEnd ;
    this.duration=(typeof(duration) == 'undefined') ? 0 : duration ;
    if (this.segmentStart == 0 && this.segmentEnd == 0) {
    	this.isSegmented = false;
    } else {
    	this.isSegmented = true;
    }
    
    

//public methods  
    this.setfileUploaded = setfileUploaded;
    this.getQueryString = getQueryString;

    function setfileUploaded() {
      this.isFileUploaded=true;
    }
    
    function getQueryString(baseUrl, moreParams) {
      var baseUrl = (typeof(baseUrl) == 'undefined') ? '' : baseUrl + '?' ;
      var moreParams = (typeof(moreParams) == 'undefined') ? '' : moreParams ;

      var queryParameters = {};
      queryParameters['uploadedFilename']=this.uploadedFilename;
      queryParameters['originalFilename']=this.originalFilename;
      queryParameters['metadataquery']=this.metadataquery;
      queryParameters['tracknr']=this.queryTrackId;
      if (this.isSegmented) {
      	queryParameters['segmentStart']=this.segmentStart;	
      	queryParameters['segmentEnd']=this.segmentEnd;	
      	queryParameters['duration']=this.duration;
      }  
      
      return baseUrl + $.param(queryParameters) + '&' + moreParams; 
    }

  }
  var relatedQuery = new relatedQueryObj();


// used to replace nl in strings with breaks
  function nl2br (str, is_xhtml) {   
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
  }  

// swap images for elements with class className
  function mouseoverImageChanger(className) {
      $("."+className)
          .mouseover(function() { 
            var src = $(this).attr("src").replace("_on.", "_over.");
              $(this).attr("src", src);
          })
          .mouseout(function() {
              var src = $(this).attr("src").replace("_over.", "_on.");
              $(this).attr("src", src);
          });
  }



// detect iphone/ipad
  function isiPhone(){
      return (
          //Detect iPhone
          (navigator.platform.indexOf("iPhone") != -1) ||
          //Detect iPad
          (navigator.platform.indexOf("iPad") != -1) ||
          //Detect iPod
          (navigator.platform.indexOf("iPod") != -1)
      );
  }
