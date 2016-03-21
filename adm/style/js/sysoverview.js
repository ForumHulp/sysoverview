$("a.simpledialog").simpleDialog({
    opacity: 0.1,
    width: '650px',
	closeLabel: '&times;'
});

$('.permissions-never').bind('mouseenter', function(){
    var $this = $(this);

    if(this.offsetWidth < this.scrollWidth && !$this.attr('title')){
        $this.attr('title', $this.text());
    }
});