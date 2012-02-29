function centerSimileAjax(date) {
    tl.getBand(0).setCenterVisibleDate(SimileAjax.DateTime.parseGregorianDateTime(date));
}

function setupFilterHighlightControls(div, timeline, bandIndices, theme) {

	//added by mack
	var button1 = document.createElement("button");
    button1.innerHTML = "Coached Call";
    div.appendChild(button1);
	
	SimileAjax.DOM.registerEvent(button1, "click", function() {
        buttonFilter(timeline, bandIndices, button1);
    });
	
	var button2 = document.createElement("button");
    button2.innerHTML = "ORS";
    div.appendChild(button2);
	
	SimileAjax.DOM.registerEvent(button2, "click", function() {
        buttonFilter(timeline, bandIndices, button2);
    });
	
	var button3 = document.createElement("button");
    button3.innerHTML = "Screen Cap";
    div.appendChild(button3);
	
	SimileAjax.DOM.registerEvent(button3, "click", function() {
        showTrack(timeline, bandIndices, "3")
    });
	
	var button = document.createElement("button");
    button.innerHTML = "Clear All";
    SimileAjax.DOM.registerEvent(button, "click", function() {
        clearAll(timeline, bandIndices);
    });
    div.appendChild(button);

	
}

var timerID = null;
function cleanString(s) {
    return s.replace(/^\s+/, '').replace(/\s+$/, '');
}

function buttonFilter(timeline, bandIndices, element)
{
	var filterMatcher = null;
	var text = element.innerHTML;
	if (text.length > 0) {
        var regex = new RegExp(text, "i");
        filterMatcher = function(evt) {
            return regex.test(evt.getText()) || regex.test(evt.getDescription());
        };
    }
	
	for (var i = 0; i < bandIndices.length; i++) {
        var bandIndex = bandIndices[i];
        timeline.getBand(bandIndex).getEventPainter().setFilterMatcher(filterMatcher);
    }
    timeline.paint();
}

function showTrack(timeline, bandIndices, track)
{
	// hacked this together
	filterMatcher = function(evt) {
		if (evt.getTrackNum() == track)
		{
			return true;
		}
		else
		{
			return false;
		}
    };

	for (var i = 0; i < bandIndices.length; i++) 
	{
        var bandIndex = bandIndices[i];
		if (i != (track-1))
		{	
			timeline.getBand(bandIndex).getEventPainter().setFilterMatcher(filterMatcher);
		}
    }
    timeline.paint();
}

function clearAll(timeline, bandIndices) {
    
    for (var i = 0; i < bandIndices.length; i++) {
        var bandIndex = bandIndices[i];
        timeline.getBand(bandIndex).getEventPainter().setFilterMatcher(null);
        timeline.getBand(bandIndex).getEventPainter().setHighlightMatcher(null);
    }
    timeline.paint();
}