<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">

				<div class="panel_s">
					<div class="panel-body">
						<?php if(has_permission('staff','','create')){ ?>
						<div class="_buttons">
              <input type="file" name="file" value="" id="file_html_data">
              <div id="table-tmp">

              </div>



						</div>
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
						<?php } ?>
						<div class="clearfix"></div>

					</div>
				</div>
			</div>
		</div>





	</div>
</div>

<div class="loader">
  <h1>Đang Phân Tích File Html <span class="bullets">.</span></h1>
</div>

<style media="screen">
.loader {
background-color:#bfcbd9;
text-align:center;
height: 100vh;
display:none;
align-items:center;
justify-content:center;
position: fixed;
top:0;
left:0;
width: 100%;
height: 100%;
z-index: 123123123123;
}
.loader.active {
  display:flex;
}
.loader h1 {
color:white;
font-family: 'arial';
font-weight: 800;
font-size: 4em;
}
.bullets{
animation: dots 2s steps(3, end) infinite;
}

@keyframes dots {
0%, 20% {
  color: rgba(0,0,0,0);
  text-shadow:
    .25em 0 0 rgba(0,0,0,0),
    .5em 0 0 rgba(0,0,0,0);}
40% {
  color: white;
  text-shadow:
    .25em 0 0 rgba(0,0,0,0),
    .5em 0 0 rgba(0,0,0,0);}
60% {
  text-shadow:
    .25em 0 0 white,
    .5em 0 0 rgba(0,0,0,0);}
80%, 100% {
  text-shadow:
    .25em 0 0 white,
    .5em 0 0 white;}}

</style>


<?php init_tail(); ?>


<script type="text/javascript">
  document.getElementById('file_html_data').addEventListener('change', getFile)


  function getFile(event) {
    $('.loader').addClass('active');
    const input = event.target
    if ('files' in input && input.files.length > 0) {
      placeFileContent(input.files[0])
    }
  }

  function placeFileContent(file) {

    readFileContent(file).then( (content) => {

      console.log("end loading");
      $('#table-tmp').append(content);

      var contentTable = $('#custom-table_wrapper').html();
      $('#table-tmp').empty();
      $('#table-tmp').append(contentTable);

      var dataSendShop = [];
      $('#table-tmp tbody tr').each(function(i, obj) {
        var shopName = $('#table-tmp tbody tr').eq(i).find('td').eq(3).find('.bold')[0].innerText;

        dataSendShop.push({shop:shopName});


      });

      var data = {};
      // if (typeof(csrfData) !== 'undefined') {
      //     data[csrfData['token_name']] = csrfData['hash'];
      // }
      data.dataSendShop = dataSendShop;


      if (dataSendShop.length > 0) {


        $.ajax({
						url: '/system/admin/customer/read_html',
						data,
						type: 'POST',
						success: function (data) {
              console.log(data);
							$('.loader').removeClass('active');
						},
						error:function(e) {
							console.log(e);
						}
				});
      }else {
        $('.loader').removeClass('active');
        alert('Không có Dữ Liệu Khách Hàng');
      }


      // target.value = content
    }).catch(error => console.log(error))
  }

  function readFileContent(file) {
    const reader = new FileReader()
    return new Promise((resolve, reject) => {
      reader.onload = event => resolve(event.target.result)
      reader.onerror = error => reject(error)
      reader.readAsText(file)
    })
  }
</script>
