Ext.define('AC.helper.Fn', {
	singleton : true,

    //Helper functions
    scrollIntoView: function(el,cmp) {
    	console.log(cmp.getScrollable().getScroller().scrollToEnd(true));
	  	// var dy = cmp.getScrollable().getElement().getY() + el.getY() - cmp.body.getY();
	  	// cmp.getScrollable().setOffset({x:0, y:-dy}, true);
	}
});