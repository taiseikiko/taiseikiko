<div class="container">
    <div class="modal fade" id="confirm" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h4 class="modal-title">&#10068;</h4> -->
                </div>
                <div class="modal-body">
                    <p id="confirm-message"></p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="btnProcess" id="btnProcess">
                    <button class="okBtn" id="confirm_okBtn">はい</button>
                    <button class="cancelBtn" id="cancelBtn" data-dismiss="modal">キャンセル</button>
                </div>
            </div>
        
        </div>
    </div>
    <div class="modal fade" id="ok" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h4 class="modal-title" style="color:white">&#10004;</h4> -->
                </div>
                <div class="modal-body">
                    <p id="ok-message"></p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="btnProcess" id="btnProcess">
                    <button class="okBtn" id="ok_okBtn">はい</button>
                </div>
            </div>
        
        </div>
    </div>
</div>
<style>

/* CSS */

    .modal{
        position:fixed;
        top:0;
        right:0;
        bottom:0;
        left:0;
        z-index:1000;
        display:none;
        overflow:hidden;
        -webkit-overflow-scrolling:touch;
        outline:0
    }
    .modal.fade .modal-dialog{
        -webkit-transform:translate(0,-25%);
        -ms-transform:translate(0,-25%);
        -o-transform:translate(0,-25%);
        transform:translate(0,-25%);
        -webkit-transition:-webkit-transform .3s ease-out;
        -o-transition:-o-transform .3s ease-out;
        transition:-webkit-transform .3s ease-out;
        transition:transform .3s ease-out;
        transition:transform .3s ease-out,-webkit-transform .3s ease-out,-o-transform .3s ease-out
    }
    .modal.in .modal-dialog{
        -webkit-transform:translate(0,0);
        -ms-transform:translate(0,0);
        -o-transform:translate(0,0);
        transform:translate(0,0)
    }
    .modal-open .modal{
        overflow-x:hidden;
        overflow-y:auto
    }
    .modal-dialog{
        position:relative;
        width:400px;
    }.modal-content{
        width: 400px;
        height: auto;
        position:relative;
        background-color:#fff;
        background-clip:padding-box;
        border:1px solid #999;
        border:1px solid rgba(0,0,0,.2);
        border-radius:12px;
        -webkit-box-shadow:0 3px 9px rgba(0,0,0,.5);
        box-shadow:0 3px 9px rgba(0,0,0,.5);
        outline:0
    }
    .modal-backdrop{
        position:fixed;
        top:0;
        right:0;
        bottom:0;
        left:0;
        z-index:900;
        background-color:#000
    }
    .modal-backdrop.fade{
        filter:alpha(opacity=0);
        opacity:0
    }
    .modal-backdrop.in{
        filter:alpha(opacity=50);
        opacity:.5
    }
    .modal-header{
        background-color: #4d9bc1;
        border-top-right-radius: 12px;
        border-top-left-radius: 12px;
        padding:5px;
        border-bottom:1px solid #e5e5e5;
        height: 20px;
    }
    .modal-header .close{
        margin-top:-2px
    }
    .modal-title{
        text-align: center;
        margin-top: -16px;
        /* line-height:1.42857143 */
    }
    .modal-body{
        position:relative;
        padding-left:10px;
        height: 30px;
        /* background-color:#e9edef; */
    }
    .modal-footer{
        padding:3px;
        text-align:right;
        border-top:1px solid #e5e5e5;
        height: 30px;
    }
    .modal-footer .btn+.btn{
        margin-bottom:0;
        margin-left:5px
    }
    .modal-footer .btn-group .btn+.btn{
        margin-left:-1px
    }
    .modal-footer .btn-block+.btn-block{
        margin-left:0
    }
    .modal-scrollbar-measure{
        position:absolute;
        top:-9999px;
        width:50px;
        height:50px;
        overflow:scroll
    }
    @media (min-width:768px){
        .modal-dialog{
            width:400px;
            margin:0 auto
        }
        .modal-content{
            -webkit-box-shadow:0 5px 15px rgba(0,0,0,.5);
            box-shadow:0 5px 15px rgba(0,0,0,.5)
        }
        .modal-sm{width:300px}
    }
    @media (min-width:992px){
        .modal-lg{width:900px}
    }
    .close{
        float:right;
        font-size:21px;
        font-weight:700;
        line-height:1;
        color:#000;
        text-shadow:0 1px 0 #fff;
        filter:alpha(opacity=20);
        opacity:.2
    }
    .close:focus,.close:hover{
        color:#000;
        text-decoration:none;
        cursor:pointer;
        filter:alpha(opacity=50);
        opacity:.5
    }
    button.close{
        padding:0;
        cursor:pointer;
        background:0 0;
        border:0;
        -webkit-appearance:none;
        -moz-appearance:none;
        appearance:none
    }
</style>