	</div>
	<!-- /Container -->
		</div>
		<!-- /main-content -->
			<!-- Footer opened -->
			<div class="main-footer ht-40">
				<div class="container-fluid pd-t-0-f ht-100p">
					<span>Copyright © 2024 <a href="#">Dgolf</a>. Developed by <a href="https://aksiteknologi.com/">Aksi Teknologi</a> All rights reserved.</span>
				</div>
			</div>
			<!-- Footer closed -->

		</div>
		<!-- End Page -->
<!-- Back-to-top -->
<a href="#top" id="back-to-top"><i class="las la-angle-double-up"></i></a>
<!-- script dewe -->
<script>
	function previewImage() {
		const image = document.querySelector('#image');
		const imgPreview = document.querySelector('.image-preview');

		imgPreview.style.display = 'block';

		const oFReader = new FileReader();
		oFReader.readAsDataURL(image.files[0]);

		oFReader.onload = function(oFREvent){
			imgPreview.src = oFREvent.target.result;
		}
	}

	function load_detail_question(id) {
        var detailElement = $('.cid_' + id);
        if (detailElement.hasClass('hide')) {
            detailElement.addClass('show').removeClass('hide').show();
            $('#t_user_id_' + id).prop('disabled', true);
        } else {
            detailElement.addClass('hide').removeClass('show').hide();
            $('#t_user_id_' + id).prop('disabled', false);
        }
    }
</script>
<!-- JQuery min js -->
<script src="/Valex/html/assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap Bundle js -->
<script src="/Valex/html/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Ionicons js -->
<script src="/Valex/html/assets/plugins/ionicons/ionicons.js"></script>
<!-- Moment js -->
<script src="/Valex/html/assets/plugins/moment/moment.js"></script>
<script src="/Valex/html/assets/plugins/raphael/raphael.min.js"></script>
<!-- eva-icons js -->
<script src="/Valex/html/assets/js/eva-icons.min.js"></script>
<!-- Rating js-->
<script src="/Valex/html/assets/plugins/rating/jquery.rating-stars.js"></script>
<script src="/Valex/html/assets/plugins/rating/jquery.barrating.js"></script>
<!-- custom js -->
<script src="/Valex/html/assets/js/custom.js"></script>
<!--- JQuery sparkline js --->
<script src="/Valex/html/assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
<!--Internal  Chart.bundle js -->
<script src="/Valex/html/assets/plugins/chart.js/Chart.bundle.min.js"></script>
<!--Internal Sparkline js -->
<script src="/Valex/html/assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
<!--Internal  Flot js-->
<script src="/Valex/html/assets/plugins/jquery.flot/jquery.flot.js"></script>
<script src="/Valex/html/assets/plugins/jquery.flot/jquery.flot.pie.js"></script>
<script src="/Valex/html/assets/plugins/jquery.flot/jquery.flot.resize.js"></script>
<script src="/Valex/html/assets/plugins/jquery.flot/jquery.flot.categories.js"></script>
<script src="/Valex/html/assets/js/dashboard.sampledata.js"></script>
<script src="/Valex/html/assets/js/chart.flot.sampledata.js"></script>
<!-- Custom Scroll bar Js-->
<script src="/Valex/html/assets/plugins/mscrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
<!--Internal Apexchart js-->
<script src="/Valex/html/assets/js/apexcharts.js"></script>
<!--Internal  Perfect-scrollbar js -->
<script src="/Valex/html/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="/Valex/html/assets/plugins/perfect-scrollbar/p-scroll.js"></script>
<!-- right-sidebar js -->
<script src="/Valex/html/assets/plugins/sidebar/sidebar.js"></script>
<script src="/Valex/html/assets/plugins/sidebar/sidebar-custom.js"></script>
<!-- Sticky js -->
<script src="/Valex/html/assets/js/sticky.js"></script>
<script src="/Valex/html/assets/js/modal-popup.js"></script>
<!-- Left-menu js-->
<script src="/Valex/html/assets/plugins/side-menu/sidemenu.js"></script>
<!-- Internal Map -->
<script src="/Valex/html/assets/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="/Valex/html/assets/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!--Internal  index js -->
<script src="/Valex/html/assets/js/index.js"></script>
<!-- Apexchart js-->
<script src="/Valex/html/assets/js/apexcharts.js"></script>
<!-- custom js -->
<script src="/Valex/html/assets/js/custom.js"></script>
<script src="/Valex/html/assets/js/custom.js"></script>
<script src="/Valex/html/assets/js/jquery.vmap.sampledata.js"></script>
<script src="/Valex/html/assets/js/jquery.vmap.sampledata.js"></script>
<!--Internal  Datepicker js -->
<script src="/Valex/html/assets/plugins/jquery-ui/ui/widgets/datepicker.js"></script>
<!--Internal  jquery.maskedinput js -->
<script src="/Valex/html/assets/plugins/jquery.maskedinput/jquery.maskedinput.js"></script>
<!--Internal  spectrum-colorpicker js -->
<script src="/Valex/html/assets/plugins/spectrum-colorpicker/spectrum.js"></script>
<!-- Internal Select2.min js -->
<script src="/Valex/html/assets/plugins/select2/js/select2.min.js"></script>
<!--Internal Ion.rangeSlider.min js -->
<script src="/Valex/html/assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
<!--Internal  jquery-simple-datetimepicker js -->
<script src="/Valex/html/assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js"></script>
<!-- Ionicons js -->
<script src="/Valex/html/assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js"></script>
<!--Internal  pickerjs js -->
<script src="/Valex/html/assets/plugins/pickerjs/picker.min.js"></script>
<!-- P-scroll js -->
<script src="/Valex/html/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="/Valex/html/assets/plugins/perfect-scrollbar/p-scroll.js"></script>
<!-- Internal form-elements js -->
<script src="/Valex/html/assets/js/form-elements.js"></script>
<!--Internal quill js -->
<script src="/Valex/html/assets/plugins/quill/quill.min.js"></script>
<!--Internal  Select2 js -->
<script src="/Valex/html/assets/plugins/select2/js/select2.min.js"></script>
<!-- Internal Form-editor js -->
<script src="/Valex/html/assets/js/form-editor.js"></script>
<!--Internal  Clipboard js-->
<script src="/Valex/html/assets/plugins/clipboard/clipboard.min.js"></script>
<script src="/Valex/html/assets/plugins/clipboard/clipboard.js"></script>
<!-- Internal Prism js-->
<script src="/Valex/html/assets/plugins/prism/prism.js"></script>
<!--Internal Sumoselect js-->
<script src="/Valex/html/assets/plugins/sumoselect/jquery.sumoselect.js"></script>
<!--Internal  Form-elements js-->
<script src="/Valex/html/assets/js/advanced-form-elements.js"></script>
<script src="/Valex/html/assets/js/select2.js"></script>
<!-- Internal Modal js-->
<script src="/Valex/html/assets/js/modal.js"></script>

</body>
</html>