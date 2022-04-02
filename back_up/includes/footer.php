<div class="app-footer">
	<div class="p-a text-xs">
		<div class="copyright_center">
			<span class="copyright_text">&nbsp;
				<span class="footer-text"><?=COPY_RIGHT_INFO?></span>
			</span>
		</div>
		<div class="pull-right back_to_top"><a id="back-to-top" href="#" title="Back to top"><i class="fa fa-long-arrow-up p-x-sm"></i></a></div>
	</div>
</div>
<!-- Start of Script box -->
<script src="<?php echo HTTP_PATH?>/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo HTTP_PATH?>/js/bootstrap-colorpicker.min.js"></script>
<script src="<?php echo HTTP_PATH?>/js/form-validation.js"></script>
<script src="<?php echo HTTP_PATH?>/js/bootstrap.js" type="text/javascript"></script>
<script src="<?php echo HTTP_PATH?>/js/bootstrap-select.js" type="text/javascript"></script>
<script src="<?php echo HTTP_PATH?>/js/common.js"></script>
<!-- autocomplelte js -->
<link rel="stylesheet" href="<?php echo HTTP_PATH?>/css/autocomplete/jquery-ui.css" />
<script src="<?php echo HTTP_PATH?>/js/autocomplete/jquery-ui.js"></script>

<script>
	
/** Script for left menu navbar **/
$(".nav > li > a").on('click', function(e){
   $(this).parent().addClass('active').siblings().removeClass('active');
    e.preventDefault();
});
	
/*Scroll to top when arrow up clicked BEGIN*/
if ($('#back-to-top').length) {
    var scrollTrigger = 100, // px
        backToTop = function () {
            var scrollTop = $(window).scrollTop();
            if (scrollTop > scrollTrigger) {
                $('#back-to-top').addClass('show');
            } else {
                $('#back-to-top').removeClass('show');
            }
        };
    backToTop();
    $(window).on('scroll', function () {
        backToTop();
    });
    $('#back-to-top').on('click', function (e) {
        e.preventDefault();
        $('html,body').animate({
            scrollTop: 0
        }, 700);
    });
}
/*Scroll to top when arrow up clicked END*/

	
/** Script for Colorpicker **/	
	
$('#colorpicker_box').colorpicker();

/** Script for tooltip **/  	
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});
	
/** Upload image button **/
document.getElementById("uploadBtn").onchange = function ()
{
    document.getElementById("uploadFile").value = this.value.replace(/^C:\\fakepath\\/i, '');
};

document.getElementById("uploadBtn2").onchange = function ()
{
    document.getElementById("uploadFile2").value = this.value.replace(/^C:\\fakepath\\/i, '');
};
	

document.getElementById("uploadBtn3").onchange = function ()
{
    document.getElementById("uploadFile3").value = this.value.replace(/^C:\\fakepath\\/i, '');
};

document.getElementById("uploadBtn4").onchange = function ()
{
    document.getElementById("uploadFile4").value = this.value.replace(/^C:\\fakepath\\/i, '');
};
document.getElementById("uploadBtn5").onchange = function ()
{
    document.getElementById("uploadFile5").value = this.value.replace(/^C:\\fakepath\\/i, '');
};
</script>
</body>
</html>