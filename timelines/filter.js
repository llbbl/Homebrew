function centerSimileAjax(date) {
    tl.getBand(0).setCenterVisibleDate(SimileAjax.DateTime.parseGregorianDateTime(date));
}

function setupFilterHighlightControls(div, timeline, bandIndices, theme) {

	//added by mack
	var buttonCall = document.createElement("button");
    buttonCall.innerHTML = "Call";
    SimileAjax.DOM.registerEvent(buttonCall, "click", function() {
        toggleTrack(timeline, 1);
    });
    div.appendChild(buttonCall);

	var buttonDesk = document.createElement("button");
    buttonDesk.innerHTML = "Desktop";
    SimileAjax.DOM.registerEvent(buttonDesk, "click", function() {
        toggleTrack(timeline, 2);
    });
    div.appendChild(buttonDesk);
	
	var buttonScreen = document.createElement("button");
    buttonScreen.innerHTML = "Screen";
    SimileAjax.DOM.registerEvent(buttonScreen, "click", function() {
        toggleTrack(timeline, 3);
    });
    div.appendChild(buttonScreen);
	
	
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

function removeTrack(timeline, track)
{
	filterMatcher = function(evt) {
		if (evt.getTrackNum() != track)
		{
			return true;
		}
		else
		{
			return false;
		}
    };

	alert(track);
	timeline.getBand(track-1).getEventPainter().setFilterMatcher(filterMatcher);
	timeline.paint();
}

function toggleTrack(timeline,track)
{
	if (bandVisible[track] == 'on')
	{
		bandVisible[track] = 'off';
	}
	else
	{
		bandVisible[track] = 'on';
	}

	filterMatcher = function(evt) {
		if (evt.getTrackNum() != track)
		{
			return true;
		}
		
		if (bandVisible[track] == 'on')
		{
			return false;
		}
		else
		{
			return true;
		}
	};
	
	timeline.getBand(0).getEventPainter().setFilterMatcher(filterMatcher);
	timeline.getBand(1).getEventPainter().setFilterMatcher(filterMatcher);
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