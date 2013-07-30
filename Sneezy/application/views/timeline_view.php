<div id="doc3" class="yui-t7">
   <div id="bd" role="main">
	   <div class="yui-g">
	     <div id='tl'></div>
	   </div>
	 </div>
</div>

<script>        
		// clean this up, not in a singleton or global variable
		var tl;
        function loadTimeline() {
            var tl_el = document.getElementById("tl");
            var eventSource1 = new Timeline.DefaultEventSource();
            
            var theme1 = Timeline.ClassicTheme.create();
            theme1.autoWidth = true; // Set the Timeline's "width" automatically.
                                     // Set autoWidth on the Timeline's first band's theme,
                                     // will affect all bands.
			//var d = "Fri June 16 2013 00:00:00 GMT-0600";
			var d = "<?php echo date("D F d Y "); ?> 00:00:00 GMT-0600";
            theme1.timeline_start = new Date(Date.UTC(2013, 3, 27));//2013-03-27 07:00:00
            theme1.timeline_stop  = new Date(Date.UTC(2160, 0, 1));
            
            //var d = Timeline.DateTime.parseGregorianDateTime("2013")
            var bandInfos = [
                Timeline.createBandInfo({
                    width:          45, // set to a minimum, autoWidth will then adjust
                    intervalUnit:   Timeline.DateTime.DAY, 
                    intervalPixels: 200,
                    eventSource:    eventSource1,
                    date:           d,
                    theme:          theme1,
                    layout:         'original'  // original, overview, detailed
                })
            ];
                                                            
            // create the Timeline
            tl = Timeline.create(tl_el, bandInfos, Timeline.HORIZONTAL);
            
            var url = '.'; // The base url for image, icon and background image
                           // references in the data
			tl.loadJSON('<?php echo base_url() . "index.php/result/get_timeline_data/"; ?>', function(json, url) {
       			eventSource1.loadJSON(json, url);
       			tl.layout(); // draw the timeline after we get all the data
   			});                                                       
            //tl.layout(); // display the Timeline
        }
        
        var resizeTimerID = null;
        function onResize() {
            if (resizeTimerID == null) {
                resizeTimerID = window.setTimeout(function() {
                    resizeTimerID = null;
                    tl.layout();
                }, 500);
            }
        }
        
        window.setTimeout(function() {
            loadTimeline();
        }, 2000);
</script>
