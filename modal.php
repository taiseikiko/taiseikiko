<div id="modal" class="modal">
  <div class="modal__content">
    <p id="modal-message"></p>
    <div style="margin-left: 200px;">
      <input class="modal-btn okBtn" id="okBtn" type="button" value="はい">
      <input class="modal-btn cancelBtn" id="cancelBtn" type="button" value="キャンセル">
  </div>
  </div>
</div>
<style>
  #modal-message {
    font-weight: 900;
  }
  .modal {
    /* visibility: hidden; */
    display: none;
    /* opacity: 0; */
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    /* display: flex; */
    align-items: baseline;
    justify-content: center;
    background: rgba(77, 77, 77, 0);
    transition: all .4s;
    z-index: 1000;
    height: 100vh;
  }

  /* .modal:target {
    visibility: visible;
    opacity: 1;
  } */

  .modal__content {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    border-radius: 18px;
    position: relative;
    width: 400px;
    max-width: 90%;
    background: #fff;
    padding: 1em 2em;
  }

  .modal__footer {
    text-align: right;
    a {
      color: #585858;
    }
    i {
      color: #d02d2c;
    }
  }
  /* .modal__close {
    position: absolute;
    top: 10px;
    right: 10px;
    color: #585858;
    text-decoration: none;
  } */

  /* CSS */
  .modal-btn {
    appearance: none;
    border: 2px solid #1a1a1a36;
    border-radius: 15px;
    box-sizing: border-box;
    color: #3B3B3B;
    cursor: pointer;
    display: inline-block;
    font-family: Roobert,-apple-system,BlinkMacSystemFont,"Segoe UI",Helvetica,Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
    font-size: 11px;
    font-weight: 900;
    line-height: normal;
    margin: 0;
    min-height: 30px;
    min-width: 0;
    outline: none;
    padding: 6px 14px;
    text-align: center;
    text-decoration: none;
    transition: all 300ms cubic-bezier(.23, 1, 0.32, 1);
    user-select: none;
    -webkit-user-select: none;
    touch-action: manipulation;
    width: 90px;
    will-change: transform;
    background-color: #0000007d!important;
  }

  .modal-btn:disabled {
    pointer-events: none;
  }

  .modal-btn:hover {
    box-shadow: rgba(0, 0, 0, 0.25) 0 8px 15px;
    transform: translateY(-1px);
    color: #3B3B3B;
    background-color: transparent;
  }

  .okBtn {
    color: #fff!important;
    background-color: #0000007d!important;
  }

  .cancelBtn {
    background-color: transparent!important;
  }

  .modal-btn:active {
    box-shadow: none;
    transform: translateY(0);
  }

  .cancelBtn {
    background-color: #fff;
  }
</style>