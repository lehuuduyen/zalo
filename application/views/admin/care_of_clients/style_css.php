


<style>
    .progressbar {
        margin: 0;
        padding: 0;
        counter-reset: step;
    }
    .progressbar li:not(.initli) {
        list-style-type: none;
        width: 25%;
        float: left;
        font-size: 10px;
        position: relative;
        text-align: center;
        /*text-transform: uppercase;*/
        color: #7d7d7d;
        z-index: 0;
    }
    .progressbar li:not(.initli):before {
        width: 10px;
        height: 10px;
        content: ' ';
        counter-increment: step;
        line-height: 51px;
        border: 5px solid #7d7d7d;
        display: block;
        text-align: center;
        margin: 0 auto 10px auto;
        border-radius: 50%;
        background-color: white;
    }
    .progressbar li:not(.initli):after {
        width: 100%!important;
        height: 2px!important;
        content: ''!important;
        position: absolute!important;
        background-color: #7d7d7d!important;
        top: 4px!important;
        left: -50%!important;
        z-index: -1!important;
    }
    .progressbar li:not(.initli):first-child:after {
        content: none;
        display: none;
    }
    .progressbar li.active {
        color: green;
    }
    .progressbar li.active:before {
        border-color: #55b776;
    }
    .progressbar li.active + li:after {
        background-color: #55b776!important;
    }

    .progressbar_img{
        text-align: center!important;
        display: flex;
        flex-direction: row;
        justify-content: center;
        margin-bottom: 0px;
    }
    ul.progressbar_img li {
        width: 25%;
        float: left;
    }

    .font11
    {
        font-size: 11px;
    }
    .btn-info.active, .btn-info:active{
        background-color: #094865;
    }
    .table-care_of_clients th, .table-care_of_clients td { white-space: nowrap; }
    .mw600{
        min-width: 600px;
    }
    .table-care_of_clients .dropdown-menu{
        top: 100%;
        bottom: auto;
    }
    #file_erience {
        width: 100%;
    }

    .table-care_of_clients .popover{
        min-width: 300px;
    }
    .table-care_of_clients td {
        padding-top: 0px;
        padding-bottom: 0px;
    }
    .mw800 p {
        margin-bottom: 0px!important;
    }
    .table-care_of_clients tbody tr td:nth-child(2), .table-care_of_clients tbody tr td:nth-child(1), .table-care_of_clients tbody tr td:nth-child(3) {
        white-space: inherit;
        min-width: 100px;
    }
    .table-care_of_clients tbody tr td:nth-child(11)
    {
        padding-top: 0px!important;
        padding-bottom: 0px!important;
    }
    .table-care_of_clients .preview_image {
         margin-bottom: 0px;
         margin-top: 0px;
    }
</style>