</div>
<div id="modalOne"></div>
<div class="modal fade" id="modal_advisory_lead" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>
<div class="modal fade" id="modal_care_of_clients" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>
<div class="modal fade" id="modal_profile_customer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>
<div class="modal fade" id="modal_profile_lead" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>
    <?php $this->load->view('messagers/manage/script_js')?>
    
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

    <?php $this->load->view('messagers/manage/include/main_js_fb'); ?>
</body>
</html>
