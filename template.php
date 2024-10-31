<?php $ids = rand();?>
   <style>
    .hidden{display:none;}
    canvas{max-width:100%}
    .canv_wrap_pdf_kenrys canvas{position: relative;cursor: pointer;}
    .canv_wrap_pdf_kenrys {
      position: relative;
      display: block;
      margin: 2em auto;
      overflow: hidden;
    }
    .canv_wrap_pdf_kenrys:hover .pageflip {
      width: 40px;
      height: 40px;
      box-shadow: -5px -5px 10px rgba(0, 0, 0, 0.4);
    }
    .pageflip {
      display: block;
      position: absolute;
      z-index: 1;
      width: 0;
      height: 0;
      bottom: 0;
      right: 0;
      background-image: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgi…pZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JhZCkiIC8+PC9zdmc+IA==");
      background-size: 100%;
      background-image: linear-gradient(to right bottom, #fbfbff52 50%, #ffffff 51%);
      transition: all 0.3s;
    }
<?php if($ani) { ?>
    @keyframes pageFlip {
        0%   {transform: rotateY(-180deg);left:-40%}
        50%   {transform: rotateY(-90deg);left:-20%}
        100% {transform: rotateY(0deg);left:0%}
    }
    
    @keyframes pageFlip-next {
        0%   {transform: rotateY(180deg);left:40%}
        50%   {transform: rotateY(90deg);left:20%}
        100% {transform: rotateY(0deg);left:0}
		
    }
<?php } ?>
       .clear{clear: both}
    
/*
    @keyframes pagesFlip {
        0%   {opacity: 0;right:0%}
        25%  {opacity: 0;right: 110%}
        50%  {opacity: .7;left:100%}
        100% {opacity: 1;left: 0%}
    }
    
    @keyframes pagesFlip-next {
        0%   {opacity: 0;left:0%}
        25%  {opacity: 0;left: 110%}
        50%  {opacity: .7;right:100%}
        100% {opacity: 1;right: 0%}
    }
*/
    .flip {
        animation: pageFlip 1s 1;
/*        box-shadow: 0px 2px 40px rgba(0, 0, 0, 0.8);*/
        z-index:9999
    }
       
    .flip-next {
        animation:  pageFlip-next 1s 1;
        z-index:9999
/*        box-shadow: 0px 2px 40px rgba(0, 0, 0, 0.8);*/
    }
    #lodingg {
    width: 10px;
    height: 10px;
    margin: 0 auto;
    padding: 50px;
    border: 7px solid transparent;
    border-radius: 50%;
    animation: tour 2s linear infinite;
    border-top: 5px solid #9999ff;
    border-bottom: 5px solid #9999ff;
}

@keyframes tour {
    0% {
    	transform: rotate(0deg);
    }
    20% {
    	transform: rotate(72deg);
    }
    40% {
    	transform: rotate(144deg);
    }
    60% {
    	transform: rotate(216deg);
    }
    80% {
    	transform: rotate(288deg);
    }
    100% {
    	transform: rotate(360deg);
    }
}
    
    #the-canvas_<?php echo $ids ?> {
        width: <?php echo $kenrys_atts['width'] ?>;
        height: <?php echo $kenrys_atts['height']?>;
    } 
       
    .wrap-box {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 9999;
        background: #000;
        width: 100%;
    }
.innder{
		   perspective: 5000px;
		   position: relative;
		   top: 50%;
           left:55%;
		   transform: translate(-50%,-50%);
		   text-align:center;
		   
       }
       <?php if($ani) { ?> 
       #prev_<?php echo $ids ?>{margin-left: -10%}
       .first{margin-left: -3%}
       <?php } ?>
       .first {
           left: 50%;
           transform: translateX(-50%);
       }

       .wrap-box  canvas {
           position: relative;
           float: left;
		   width:45%;
       }
</style>
       

<div class="col-12">
<a id="download" href="?pfbk_file=<?php echo $kenrys_atts['src'] ?>">
<button>Download</button>
    </a>
<?php if(!$ani) { ?>
  <button id="prev_<?php echo $ids ?>">Previous</button>
  <button id="next_<?php echo $ids ?>">Next</button>
<?php } ?>



  <span class="<?php echo $dos ?>">Page: <span id="page_num_<?php echo $ids ?>"></span> / <span id="page_count_<?php echo $ids ?>"></span></span>
  </div>
   <div class="canv_wrap_pdf_kenrys">
       <div class="pageflip"></div>
       <a <?php if($ani) {echo ' href="#"';} ?> ><canvas  class="hidden" id="the-canvas_<?php echo $ids ?>" ></canvas></a>
      
        <div id="lodingg"></div>
   </div>
    




<script>
    
jQuery( document ).ready( function () {
    <?php if($ani) { ?>


    // If absolute URL from the remote server is provided, configure the CORS
// header on that server.
var url = "<?php echo $kenrys_atts['src'] ?>";
//var url = '//cdn.mozilla.net/pdfjs/helloworld.pdf';

// Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window['pdfjs-dist/build/pdf'],
    lodingg = document.getElementById("lodingg");

// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = '<?php echo plugin_dir_url(__FILE__) ?>js/pdf.worker.js';

// Asynchronous download of PDF
var loadingTask = pdfjsLib.getDocument(url);
loadingTask.promise.then(function(pdf) {
  console.log('PDF loaded');
  
  // Fetch the first page
  var pageNumber = 1;
  pdf.getPage(pageNumber).then(function(page) {
    console.log('Page loaded');
    
    var scale = 1.5;
    var viewport = page.getViewport(scale);

    // Prepare canvas using PDF page dimensions
    var canvas = document.getElementById('the-canvas_<?php echo $ids ?>');
    var context = canvas.getContext('2d');
    canvas.height = viewport.height;
    canvas.width = viewport.width;

    // Render PDF page into canvas context
    var renderContext = {
      canvasContext: context,
      viewport: viewport
    };
    var renderTask = page.render(renderContext);
    renderTask.then(function () {
    canvas.style.display    = 'block';
    lodingg.style.display   = 'none';
    });
  });
}, function (reason) {
  // PDF loading error
  console.error(reason);
});
    <?php } else {?>
    // If absolute URL from the remote server is provided, configure the CORS
// header on that server.
var url = "<?php echo $kenrys_atts['src'] ?>";

// Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window["pdfjs-dist/build/pdf"];
console.log(pdfjsLib);
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc =  '<?php echo plugin_dir_url(__FILE__) ?>js/pdf.worker.js';
var pdfDoc = null,
    pageNum = 1,
    pageRendering = false,
    pageNumPending = null,
    scale = 1.5,
    canvas = document.getElementById("the-canvas_<?php echo $ids ?>"),
    lodingg = document.getElementById("lodingg"),
    ctx = canvas.getContext("2d");


/**
 * Get page info from document, resize canvas accordingly, and render page.
 * @param num Page number.
 */
function renderPage(num) {
  pageRendering = true;
  // Using promise to fetch the page
  pdfDoc.getPage(num).then(function(page) {
    var viewport = page.getViewport(scale);
    canvas.height = viewport.height;
    canvas.width = viewport.width;

    canvas.style.display    = 'block';
    lodingg.style.display   = 'none';
    // Render PDF page into canvas context
    var renderContext = {
      canvasContext: ctx,
      viewport: viewport
    };
    var renderTask = page.render(renderContext);

    // Wait for rendering to finish
    renderTask.promise.then(function() {
      pageRendering = false;
      if (pageNumPending !== null) {
        // New page rendering is pending
        renderPage(pageNumPending);
        pageNumPending = null;
      }
    });
  });

  // Update page counters
  document.getElementById("page_num_<?php echo $ids ?>").textContent = num;
}

/**
 * If another page rendering in progress, waits until the rendering is
 * finised. Otherwise, executes rendering immediately.
 */
function queueRenderPage(num) {
  
if (pageRendering) {
    pageNumPending = num;
  } else {
    renderPage(num);
  }
    //jQuery('.canv_wrap_pdf_kenrys canvas').css({left:"90%"}).animate({left:'0'})
    //jQuery('.canv_wrap_pdf_kenrys canvas').addClass('flip').delay(9000000).removeClass('flip')
    
}

/**
 * Displays previous page.
 */
function onPrevPage() {
  if (pageNum <= 1) {
    return;
  }
    var trans = jQuery('#the-pdf_<?php echo $ids ?>').addClass('flip')
    setTimeout(function() {
        trans.removeClass('flip')
    }, 1000);
  pageNum--;
  queueRenderPage(pageNum);

    
}
document.getElementById("prev_<?php echo $ids ?>").addEventListener("click", onPrevPage);
    

/**
 * Displays next page.
 */
function onNextPage() {
  if (pageNum >= pdfDoc.numPages) {
    return;
  }
       var trans = jQuery('#the-pdf_<?php echo $ids ?>').addClass('flip-next')
    setTimeout(function() {
        trans.removeClass('flip-next')
    }, 1500);
  pageNum++;
  queueRenderPage(pageNum);
}
document.getElementById("next_<?php echo $ids ?>").addEventListener("click", onNextPage);
canvas.addEventListener("click", onNextPage);


/**
 * Asynchronously downloads PDF.
 */
pdfjsLib.getDocument(url).then(function(pdfDoc_) {
  pdfDoc = pdfDoc_;
  document.getElementById("page_count_<?php echo $ids ?>").textContent = pdfDoc.numPages;

  // Initial/first page rendering
  renderPage(pageNum);
});
    <?php } ?>
});
<?php if($ani) { ?>
    


    var x = 1,
    html = '<div class="wrap-box"><div class="innder"><canvas  class="hidden first" id="the-pdf_even_<?php echo $ids ?>" ></canvas><canvas  class="hidden" id="the-pdf_odd_<?php echo $ids ?>" ></canvas>  <span id="pleft" class="left"></span><span id="pright" class="right"></span><div class="clear" ></div><button id="prev_<?php echo $ids ?>">Previous</button><button id="next_<?php echo $ids ?>">Next</button><button id="close">close</button></div></div>' 
    jQuery('#the-canvas_<?php echo $ids ?>').click(function() {
        if(x !== 2) {
            jQuery(this).parent().parent().parent().append(html);
            x = 2;
            add_even_pdf();
        }
        

    })
     jQuery('#close').live('click', function() {
       jQuery('.wrap-box').fadeOut('slow', function () {jQuery(this).remove()})
        x = 1
        })
     
	<?php } ?>
    function add_pdf() {
// If absolute URL from the remote server is provided, configure the CORS
// header on that server.
var url = "<?php echo $kenrys_atts['src'] ?>";

// Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window["pdfjs-dist/build/pdf"];
console.log(pdfjsLib);
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc =  '<?php echo plugin_dir_url(__FILE__) ?>js/pdf.worker.js';
var pdfDoc = null,
    pageNum = 1,
    pageRendering = false,
    pageNumPending = null,
    scale = 1.5,
    canvas = document.getElementById("the-pdf_<?php echo $ids ?>"),
    lodingg = document.getElementById("lodingg"),
    ctx = canvas.getContext("2d");


/**
 * Get page info from document, resize canvas accordingly, and render page.
 * @param num Page number.
 */
function renderPage(num) {
  pageRendering = true;
  // Using promise to fetch the page
  pdfDoc.getPage(num).then(function(page) {
    var viewport = page.getViewport(scale);
    canvas.height = viewport.height;
    canvas.width = viewport.width;

    canvas.style.display    = 'block';
    lodingg.style.display   = 'none';
    // Render PDF page into canvas context
    var renderContext = {
      canvasContext: ctx,
      viewport: viewport
    };
    var renderTask = page.render(renderContext);

    // Wait for rendering to finish
    renderTask.promise.then(function() {
      pageRendering = false;
      if (pageNumPending !== null) {
        // New page rendering is pending
        renderPage(pageNumPending);
        pageNumPending = null;
      }
    });
  });

  // Update page counters
  document.getElementById("page_num_<?php echo $ids ?>").textContent = num;
}

/**
 * If another page rendering in progress, waits until the rendering is
 * finised. Otherwise, executes rendering immediately.
 */
function queueRenderPage(num) {
  
if (pageRendering) {
    pageNumPending = num;
  } else {
    renderPage(num);
  }
    //jQuery('.canv_wrap_pdf_kenrys canvas').css({left:"90%"}).animate({left:'0'})
    //jQuery('.canv_wrap_pdf_kenrys canvas').addClass('flip').delay(9000000).removeClass('flip')
    
}

/**
 * Displays previous page.
 */
function onPrevPage() {
  if (pageNum <= 1) {
    return;
  }
    var trans = jQuery('#the-pdf_<?php echo $ids ?>').addClass('flip')
    setTimeout(function() {
        trans.removeClass('flip')
    }, 1000);
  pageNum--;
  queueRenderPage(pageNum);

    
}
document.getElementById("prev_<?php echo $ids ?>").addEventListener("click", onPrevPage);
    

/**
 * Displays next page.
 */
function onNextPage() {
  if (pageNum >= pdfDoc.numPages) {
    return;
  }
       var trans = jQuery('#the-pdf_<?php echo $ids ?>').addClass('flip-next')
    setTimeout(function() {
        trans.removeClass('flip-next')
    }, 1500);
  pageNum++;
  queueRenderPage(pageNum);
}
document.getElementById("next_<?php echo $ids ?>").addEventListener("click", onNextPage);
//canvas.addEventListener("click", onNextPage);


/**
 * Asynchronously downloads PDF.
 */
pdfjsLib.getDocument(url).then(function(pdfDoc_) {
  pdfDoc = pdfDoc_;
  document.getElementById("page_count_<?php echo $ids ?>").textContent = pdfDoc.numPages;

  // Initial/first page rendering
  renderPage(pageNum);
});
}
    
    
    function add_even_pdf() {
    // If absolute URL from the remote server is provided, configure the CORS
// header on that server.
var url = "<?php echo $kenrys_atts['src'] ?>";

// Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window["pdfjs-dist/build/pdf"];
console.log(pdfjsLib);
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc =  '<?php echo plugin_dir_url(__FILE__) ?>js/pdf.worker.js';
var pdfDoc = null,
    pageNum = 1,
    pageRendering = false,
    pageNumPending = null,
    scale = 1.5,
    canvas = document.getElementById("the-pdf_even_<?php echo $ids ?>"),
    canvas_1 = document.getElementById("the-pdf_odd_<?php echo $ids ?>"),
    lodingg = document.getElementById("lodingg"),
    ctx = canvas.getContext("2d");


/**
 * Get page info from document, resize canvas accordingly, and render page.
 * @param num Page number.
 */
function renderPage(num) {
  pageRendering = true;
  // Using promise to fetch the page
  pdfDoc.getPage(num).then(function(page) {
    var viewport = page.getViewport(scale);
    canvas.height = viewport.height;
    canvas.width = viewport.width;

    canvas.style.display    = 'block';
    lodingg.style.display   = 'none';
    // Render PDF page into canvas context
    var renderContext = {
      canvasContext: ctx,
      viewport: viewport
    };
    var renderTask = page.render(renderContext);

    // Wait for rendering to finish
    renderTask.promise.then(function() {
      pageRendering = false;
      if (pageNumPending !== null) {
        // New page rendering is pending
        renderPage(pageNumPending);
        pageNumPending = null;
      }
    });
  });

  // Update page counters
  document.getElementById("page_num_<?php echo $ids ?>").textContent = num;
}

/**
 * If another page rendering in progress, waits until the rendering is
 * finised. Otherwise, executes rendering immediately.
 */
function queueRenderPage(num) {

if (pageRendering) {
    pageNumPending = num;
  } else {
    renderPage(num);
  }
    //jQuery('.canv_wrap_pdf_kenrys canvas').css({left:"90%"}).animate({left:'0'})
    //jQuery('.canv_wrap_pdf_kenrys canvas').addClass('flip').delay(9000000).removeClass('flip')
    
}

/**
 * Displays previous page.
 */
function onPrevPage() {
 if (pageNum <= 1) {
    return;
  }

var trans = jQuery('#the-pdf_odd_<?php echo $ids ?>').addClass('flip').removeClass('first')
setTimeout(function() {
trans.removeClass('flip')
}, 1500);

canvas = document.getElementById("the-pdf_odd_<?php echo $ids ?>")
ctx = canvas.getContext("2d");
  pageNum--;
  queueRenderPage(pageNum);
jQuery('#the-pdf_odd_<?php echo $ids ?>').css({right:0,transform:'none',left:''});
jQuery('#the-pdf_odd_<?php echo $ids ?>').css({left:0});
    
}
        
function onprevPage_again() {

canvas = document.getElementById("the-pdf_even_<?php echo $ids ?>")
ctx = canvas.getContext("2d");
  pageNum--;
  queueRenderPage(pageNum);
}
        
        

    
function my_prev() {
    
   if (pageNum <= 1) {
    return;
  }
    onPrevPage();
    setTimeout(function() {
    onprevPage_again()
    }, 50);

        }
document.getElementById("prev_<?php echo $ids ?>").addEventListener("click", my_prev);
/**
 * Displays next page.
 */
function onNextPage() {
  if (pageNum >= pdfDoc.numPages) {
    return;
  }

var trans = jQuery('#the-pdf_even_<?php echo $ids ?>').addClass('flip-next').removeClass('first')
setTimeout(function() {
trans.removeClass('flip-next')
}, 1500);
canvas = document.getElementById("the-pdf_even_<?php echo $ids ?>")
ctx = canvas.getContext("2d");
    
  pageNum++;
  queueRenderPage(pageNum);
}
function onNextPage_again() {

canvas = document.getElementById("the-pdf_odd_<?php echo $ids ?>")
ctx = canvas.getContext("2d");
  pageNum++;
  queueRenderPage(pageNum);
}
        
function my_next() {
    if (pageNum >= pdfDoc.numPages) {
    return;
  }
    onNextPage();
    setTimeout(function() {
    onNextPage_again();
    }, 50);
    


        }
document.getElementById("next_<?php echo $ids ?>").addEventListener("click", my_next);
//canvas.addEventListener("click", onNextPage);


/**
 * Asynchronously downloads PDF.
 */
pdfjsLib.getDocument(url).then(function(pdfDoc_) {
  pdfDoc = pdfDoc_;
  document.getElementById("page_count_<?php echo $ids ?>").textContent = pdfDoc.numPages;

  // Initial/first page rendering
  renderPage(pageNum);
});
}
    
    /* test  */
 
</script>
