
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-start d-block d-md-inline-block mt-25">COPYRIGHT &copy; 2022<a class="ms-25" href="#" target="_blank">swipe up</a><span class="d-none d-sm-inline-block">, All rights Reserved</span></span><span class="float-md-end d-none d-md-block">Develop by swipe up<i data-feather="heart"></i></span></p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->


    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('public/app-assets/vendors/js/vendors.min.js') }}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('public/app-assets/vendors/js/charts/chart.min.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/extensions/moment.min.js') }}"></script>
<!-- 
    <script src="{{ asset('public/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/tables/datatable/jszip.min.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script> -->

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('public/app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('public/app-assets/js/core/app.js') }}"></script>
    <script src="{{ asset('public/app-assets/js/scripts/components/components-tooltips.js') }}"></script>
    <!-- END: Theme JS-->

    <script src="{{ asset('public/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
    <script src="{{ asset('public/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('public/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>

    <!-- ck editor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/12.0.0/classic/ckeditor.js"></script>
    <!-- BEGIN: Page JS-->
    <script src="{{ asset('public/app-assets/js/scripts/pages/dashboard-analytics.js') }}"></script>
    <script src="{{ asset('public/app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script>
    <script src="{{ asset('public/app-assets/js/scripts/pages/app-invoice-list.js') }}"></script>
    <script src="{{ asset('public/app-assets/js/scripts/charts/chart-chartjs.js') }}"></script>
    <!-- END: Page JS-->

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#datatables').DataTable();
        });
    </script>
    <!-- refresh page on reset button on click -->
    <script type="text/javascript">
        $(document).ready(function () {
            $(".reset").click(function () {
                location.reload(true);
            });
        });
    </script>

    <script type="text/javascript">
    $(document).ready(function(){ 
        // change status js
        $(document).ready(function() {
            $('#status-model').on('show.bs.modal', function (e) {
                if (e.namespace === 'bs.modal') {
                    var opener = e.relatedTarget;
                    var user_id         =$(opener).attr('data-id');
                    $('#status_model').find('[name="id"]').val(user_id);
                }
            });
        });

        // delete js
        $(document).ready(function() {
            $('#delete-model').on('show.bs.modal', function (e) {
                if (e.namespace === 'bs.modal') {
                    var opener=e.relatedTarget;
                    var id         =$(opener).attr('data-id');
                    $('#delete_model').find('[name="id"]').val(id);
                }
            });
        });
    });
</script>
    
    <!-- ck editor -->
    <script>
        ClassicEditor
            .create( document.querySelector('#editor'))
            .catch( error => {
                console.error( error );
            } );
    </script>

