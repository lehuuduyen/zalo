<style type="text/css">
    body{
        margin:0;
        padding:0;
    }
    .img
    { background:#ffffff;
        padding:12px;
        border:1px solid #999999; }
    .shiva{
        -moz-user-select: none;
        background: #2A49A5;
        border: 1px solid #082783;
        box-shadow: 0 1px #4C6BC7 inset;
        color: white;
        padding: 3px 5px;
        text-decoration: none;
        text-shadow: 0 -1px 0 #082783;
        font: 12px Verdana, sans-serif;}
</style>
<html>
<body style="background-color:#dfe3ee;">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div id="outer" style="margin:0px; width:100%; height:90px;background-color:#3B5998;">
</div>
<div id="main" style="height:800px; width:100%">
    <form id="form1" runat="server">
        <input type="file" id="imgInp" capture="camera">

        <img id="blah" src="#" alt="your image" />
    </form>
</div>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imgInp").change(function(){
        readURL(this);
    });
</script>
</body>
</html>