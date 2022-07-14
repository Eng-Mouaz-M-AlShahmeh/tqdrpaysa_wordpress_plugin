<?php
/**
 * Plugin Name: TQDR Pay Sa
 * Description: TQDR is a Saudi digital platform owned by TQDR Commercial Brokerage Corporation.
 * Version:     1.0.1
 * Author:      Eng Mouaz M. Al-Shahmeh
 * Author URI:  https://twitter.com/mouaz_m_shahmeh
 * License:     GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if(is_admin()) {
    add_action('admin_menu', 'custom_menu');

    function custom_menu() { 

        add_menu_page( 
            'TQDRPaySA', 
            'TQDRPaySA', 
            'edit_pages', 
            'tqdrpaysa', 
            'admin_tqdrpaysa', 
            'https://tqdr.com.sa/site/assets/img/icon/logo5.png' 
      
           );
      }

    function admin_tqdrpaysa() {
        echo '
        <div style="margin: 50px;">
            <div style="margin: 20px;">
            <!-- TODO: change api key as it is in contract with TQDRPaySA -->
            TQDR API Key: <input type="text" value="123abc" placeholder="TQDR API Key" name="apikey"/>
            </div>
            <div style="margin: 20px;">
            <!-- TODO: change store id as it is in contract with TQDRPaySA -->
            TQDR Store ID: <input type="text" value="123" placeholder="TQDR Store ID" name="store"/>
            </div>
        </div>
        ';
    }  
}

if(!is_admin() ) {
?>

<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<div class="m-3">
<div class="mw-50 w-50 container bg-white border border-info text-center">
    <form id="tqdrpaysa_form" action="https://tqdr.com.sa/api/invoiceorder/pay" method="POST">
        <!-- TODO: get api key from contarct with TQDRPaySA -->
        <input name="apikey" type="hidden" value="123abc" />
        <!-- TODO: get store id from contarct with TQDRPaySA -->
        <input name="store" type="hidden" value="123" />

        <div class="row col-12 m-3">
            <div class="col-9"><input required class="form-control" name="phone" type="text" placeholder="05xxxxxxxx" value="" /></div>
            <div class="col-3 text-right"> <span style="color: red;">*</span> رقم الجوال </div>
        </div>
         
        <div class="row col-12 m-3">
            <div  class="col-9"><input required class="form-control" name="amount" type="text" placeholder="ادخل قيمة" value="" /></div>
            <div  class="col-3 text-right"><span style="color: red;">*</span> المبلغ </div>
        </div>

        <div class="row col-12 m-3">
            <div class="col-8"><input required class="form-control" name="order_number" type="text" placeholder="000-0000" value="" /></div>
            <div class="col-1 text-right"> <button id="addRow" type="button" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="للدفع باكثر من رقم ايصال">+</button> </div>
            <div id="add" class="col-3 text-right"><span style="color: red;">*</span> رقم الايصال </div>
        </div>

        <div id="newRow"></div>

        <div class="row col-12 m-3">
            <span class="col-12 text-center">
                <button class="btn btn-warning" style="color: white;">ارسال</button>
            </span>
        </div>

        
        <div id="result"></div>


    </form>
</div>
</div>

<script type="text/javascript">
// add row
$("#addRow").click(function () {
    var html = '';
    html += '<div id="inputFormRow">';
    html += '<div class="row col-12 m-3">';
    html += '<div class="col-8"><input class="form-control" name="order_number" type="text" placeholder="000-0000" value="" /></div>';
    html += '<div class="col-1 text-right"> <button id="removeRow" type="button" class="btn btn-danger">-</button> </div>';
    html += '<div id="add" class="col-3"><span style="color: white;">*</span></div>';
    html += '</div>';
    html += '</div>';

    $('#newRow').append(html);
});

// remove row
$(document).on('click', '#removeRow', function () {
    $(this).closest('#inputFormRow').remove();
});

// Attach a submit handler to the form
$( "#tqdrpaysa_form" ).submit(function( event ) {
 
 // Stop form from submitting normally
 event.preventDefault();

 // Get some values from elements on the page:
 var $form = $( this ),
 apikey = $form.find( "input[name='apikey']" ).val(),
 store = $form.find( "input[name='store']" ).val(),
 phone = $form.find( "input[name='phone']" ).val(),
 amount = $form.find( "input[name='amount']" ).val();
//  order_number = $form.find( "input[name='order_number']" ).val();

 var firstoptoins = {};
 var arr = [];

 $form.find( "input[name='order_number']" ).each(function () {
        arr.push($(this).val()); 
 });

 $.each(arr , function(index, val) { 
    firstoptoins[index] = val;
 });
 
 var msg_res =''; //store the previous response

 $.ajax({
    url: $form.attr( "action" ),
    type: 'post',
    dataType: 'json',
    contentType: 'application/json',
    success: function(response){
      $("#message").html(response);
      //if response changed, not the same as in msg_res
      if(response != msg_res){
        msg_res = response; //store new response
        alert('Successful TQDR Payment');
      }
     },
     complete: function(){
      setTimeout(ajax,1000);
     },
    data: JSON.stringify({
        "apikey": apikey,
        "amount": amount,
        "phone": phone,
        "store": store,
        "order_number": firstoptoins,
    })
 }).fail(function (data) {
    alert("TQDR Payment: " + JSON.parse(data.responseText).message);
});


});
</script>


<?php
}
?>
