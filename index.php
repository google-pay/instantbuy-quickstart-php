<?php

/**
 * Copyright 2013 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the 'License');
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an 'AS IS' BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 *
 * @version 1.0
 *
 *  For this to work properly, have the following in the httpd.conf
 *  within the Directory or VirtualHost directive. 
 *  Options -MultiViews
 *
 *  <IfModule mod_rewrite.c>
 *    RewriteEngine On
 *    RewriteBase /
 *    RewriteCond %{REQUEST_FILENAME} !-f
 *    RewriteCond %{REQUEST_FILENAME} !-d
 *    RewriteRule . /index.php [L,NC,QSA]
 *  </IfModule>
 *  This allows all requests to land up in index.php which becomes 
 *  a request router.
 */

  include 'jwt.php';
  require_once 'masked_wallet.php';
  require_once 'full_wallet.php';
  require_once 'notify_status.php';
  require_once 'util.php';
  include 'config.php';

  $ruri = $_SERVER['REQUEST_URI'];
  $method = $_SERVER['REQUEST_METHOD'];
  $content = $_SERVER['CONTENT_TYPE'];
  
  

  $inputJSON = file_get_contents('php://input');
  $input= json_decode( $inputJSON, true);
  // remove the leading slash from RURI to get the request type
  $request_type = substr($ruri, 1);
  
  switch($request_type) {
    case 'masked-wallet':
      if(strcasecmp($method, 'POST') == 0) {
        MaskedWallet::post($input);  
      } else if(strcasecmp($method, 'PUT') == 0) {
        MaskedWallet::put($input); 
      } else {
        header('HTTP/1.0 400 Unsupported Method', true, 400);
        echo 'Expected only POST and PUT with Masked Wallet Request, received '. $method ;
        exit();
      }
      break;
    case 'full-wallet':
      if(strcasecmp($method, 'POST') == 0) {
        FullWallet::post($input);  
      } else {
        header('HTTP/1.0 400 Unsupported Method', true, 400);
        echo 'Expected only POST with Full Wallet Request, received '. $method ;
        exit();
      }
      break;
    case 'notify-transaction-status':
      if(strcasecmp($method, 'POST') == 0) {
        NotifyStatus::post($input);  
      } else {
        header('HTTP/1.0 400 Unsupported Method', true, 400);
        echo 'Expected only POST with Notify Status Request, received '. $method ;
        exit();
      }
      break;
    default:
      EchoIndex();
  }
  
  function EchoIndex() {
	echo <<< INDEX
		<html>
		<head>
		  <title>Instant Buy Simple Demo</title>
		  <style>
		  html, body{
		    padding: 0px;
		    margin: 0px;
		    font-family: Arial, Helvetica, sans-serif;
		    height:100%;
		    overflow:hidden;
		  }

		  .left{
		   display:block;
		   float:left;
		   width:330px;
		   height:100%;
		   border-right: 1px solid #e5e5e5;
		   margin-right:-1px;
		 }

		 .label {
		  font-weight:520;
		  font-size:14px;
		  padding: 15px 0px 0px 30px;
		  height: 30px;
		  border-top: 1px solid #e5e5e5;
		  border-bottom: 1px solid #e5e5e5;
		  margin: -1px 0px 0px 0px;
		}
		.border_bottom{
		  border-bottom: 1px solid #e5e5e5;
		}

		.right{
		  display:block;
		  float:left;
		}

		.content{
		  font-size:12px;
		  margin-top:0px;
		  margin-left:25px;
		  padding:5px;
		}

		.hidden{
		  display:none;
		}

		.log_message{
		  margin: 0px 20px 0px 20px;
		  border-bottom: 1px solid #e5e5e5;
		  padding-top: 20px;
		  padding-bottom: 20px;
		  display:inline:block;
		}

		#output{
		  word-wrap:break-word;
		  overflow:scroll;
		  height:80%;
		}

		#logo{
		  background-repeat: no-repeat;
		  background-position: -63px 0;
		  display: block;
		  height: 37px;
		  width: 95px;
		  background-image: url('https://ssl.gstatic.com/gb/images/k1_a31af7ac.png');
		  background-size: 294px 45px;
		}

		#header_bar {
		  padding-top:15px;
		  position: relative;
		  background: #f1f1f1;
		  height: 50px;
		  line-height: 50px;
		  padding-left: 44px;
		  border-bottom: 1px solid #e5e5e5;
		  z-index: 40;
		  vertical-align: top;
		}

		#subtitle {
		  position: relative;
		  line-height: 50px;
		  padding-top: 10px;
		  padding-left: 44px;
		  z-index: 40;
		  height: 40px;
		}

		#subtitle_text {
		  line-height: 29px;
		  color: #DD4B39;
		  white-space: nowrap;
		  font-size: 20px;
		}

		.label_sub {
		  color: #DD4B39;
		}

		p{
		  display:block;
		}
		#body {
		  height:100%;
		}
		.bg_grey {
		  background: #f1f1f1;
		}

		.dark_grey {
		  color:#777;
		  font-weight:200;
		}

		.kd-button-submit {
		  border: 1px solid #3079ed;
		  color: #FFF;
		  background-color: #4d90fe;
		  background-image: -webkit-gradient(linear,left top,left bottom,from(#4d90fe),to(#4787ed));
		  background-image: -webkit-linear-gradient(top,#4d90fe,#4787ed);
		  background-image: -moz-linear-gradient(top,#4d90fe,#4787ed);
		  background-image: -ms-linear-gradient(top,#4d90fe,#4787ed);
		  background-image: -o-linear-gradient(top,#4d90fe,#4787ed);
		  background-image: linear-gradient(top,#4d90fe,#4787ed);
		  filter: progid:DXImageTransform.Microsoft.gradient(startColorStr='#4d90fe',EndColorStr='#4787ed');
		}
		.kd-button-submit:hover {
		  border: 1px solid #2f5bb7;
		  color: #FFF;
		  background-color: #357ae8;
		  background-image: -webkit-gradient(linear,left top,left bottom,from(#4d90fe),to(#357ae8));
		  background-image: -webkit-linear-gradient(top,#4d90fe,#357ae8);
		  background-image: -moz-linear-gradient(top,#4d90fe,#357ae8);
		  background-image: -ms-linear-gradient(top,#4d90fe,#357ae8);
		  background-image: -o-linear-gradient(top,#4d90fe,#357ae8);
		  background-image: linear-gradient(top,#4d90fe,#357ae8);
		  filter: progid:DXImageTransform.Microsoft.gradient(startColorStr='#4d90fe',EndColorStr='#357ae8');
		}
		.kd-button-submit:active, .kd-button-submit:focus:active, .kd-button-submit.focus:active {
		  -webkit-box-shadow: inset 0px 1px 2px rgba(0,0,0,0.3);
		  -moz-box-shadow: inset 0px 1px 2px rgba(0,0,0,0.3);
		  box-shadow: inset 0px 1px 2px rgba(0,0,0,0.3);
		}

		.button_size{
		  width:100px;
		  display:block;
		  padding:7px;
		  font-size:12px;
		}
		  </style>
		  <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		  <script type="text/javascript"
		  src="https://wallet-web.sandbox.google.com/online/v2/merchant/merchant.js"></script>
		  <script type="text/javascript">
		var OauthClientId = "ENTER_CLIENTID_HERE";

		//Information for Masked Wallet request
		var MaskedWalletJson = {
		  estimatedTotalPrice: "250000000.00",
		  currencyCode: "USD"
		};

		//Json representation for items purchased
		var FullWalletJson = {
			googleTransactionId: "placeholder",
		  cart: {
		    currencyCode: "USD",
		    totalPrice: "287520000.00",
		    lineItems: [{
		      description: "Fantastic shoe, model xyz",
		      quantity: 2,
		      unitPrice: "125000000.00",
		      totalPrice: "250000000.00",
		      currencyCode: "USD",
		      isDigital: false
		    }, {
		      description: "Sales tax",
		      totalPrice: "22180000.00",
		      currencyCode: "USD",
		      role: "TAX"
		    }, {
		      description: "Shipping via UPS",
		      totalPrice: "15340000.00",
		      currencyCode: "USD",
		      role: "SHIPPING"
		    }]
		  }
		};

		//Json representation for Transaction Status Json request.
		var TransactionStatusJson = {};

		//Try to authorize for preauth
		google.wallet.online.authorize({
		  "clientId": OauthClientId,
		  "callback": function(tokenObj) {
		    console.log(tokenObj);
		  },
		})

		//********Masked Wallet Request related functions********
		//Handle the Jwt returned from the server and create the Wallet button

		function handleMaskedWalletJwt(data) {
		  display("Received Masked Wallet JWT  " + data);
		  display("Generating Google Wallet Buy button");
		  google.wallet.online.createWalletButton({
		    jwt: data,
		    success: maskedWalletSuccess,
		    failure: maskedWalletFailure,
		    ready: buttonReady
		  });
		}

		//Callback for Masked Wallet success

		function maskedWalletSuccess(response) {
		  display("Received Masked Wallet Response  " + JSON.stringify(response, null, 2));
		  FullWalletJson.googleTransactionId = response.response.response.googleTransactionId ;
		  //Enable Full Wallet request button
		  document.getElementById("full").disabled = false;
		  toggleNextStep();
		}

		//Callback for Masked Wallet failure

		function maskedWalletFailure(response) {
		  display("Masked Wallet failure check the browser console");
		  console.log(response);
		}

		//Callback for Wallet button generation

		function buttonReady(params) {
		  if (params.status == "SUCCESS") {
		    if (document.readyState === "interactive" || document.readyState === "complete") {
		      addButton(params.walletButtonElement);
		    } else {
		      document.addEventListener("DOMContentLoaded", addButton(params.walletButtonElement));
		    }
		  }
		}

		function addButton(button) {
		  display("Generation complete, adding Buy button to page");
		  var holder = document.getElementById("wallet-button-holder");
		  if (holder.hasChildNodes()) holder.removeChild(holder.firstChild);
		  holder.appendChild(button);
		  toggleNextStep();
		}

		//********Full Wallet Request related functions********
		//Handle the Jwt returned from the server and request the Full Wallet

		function handleFullWalletJwt(data) {
		  display("Received Full Wallet JWT, requesting Full Wallet  " + data);
		  //Request Full Wallet
		  google.wallet.online.requestFullWallet({
		    jwt: data,
		    success: fullWalletSuccess,
		    failure: fullWalletFailure
		  });
		}

		//Callback for Full Wallet success

		function fullWalletSuccess(response) {
		  display("Received Full Wallet Response  " + JSON.stringify(response, null, 2));
		  TransactionStatusJson.jwt = response.jwt;

		  $.post("notify-transaction-status", JSON.stringify(TransactionStatusJson), handleTransactionStatusJwt);
		  display("Requesting Transaction Status Notification JWT from server");
		  toggleNextStep();
		}

		//Callback for Full Wallet failure

		function fullWalletFailure(response) {
		  display("Full Wallet failure check the browser console");
		  console.log(response);
		}

		//********Transaction Status Notification related functions********
		//Handle the Jwt returned fromt he server and notify Wallet

		function handleTransactionStatusJwt(data) {
		  display("Received Transaction Status Notification JWT  " + data);
		  display("Notifying Google of Transaction Status");
		  //Notify Wallet
		  google.wallet.online.notifyTransactionStatus({
		    jwt: data
		  });
		  display("Transaction complete");
		}

		//Helper function to display request and responses

		function display(content) {
		  var message = document.createElement("pre");
		  message.className = "log_message";
		  message.innerText = content;
		  document.getElementById("output").appendChild(message);
		  var container = document.getElementById("output");
		  container.scrollTop = container.scrollHeight;
		}

		var ani = {
		  easing: 'swing',
		  duration: 250,
		  callback: function() {}
		};

		//Adding zippy functionality to left nav
		(function($) {
		  $.fn.zippify = function(options) {

		    return this.each(function(i, el) {
		      var content = $(el).find(".content");
		      var title = $(el).find("h2");
		      title.click(function() {
		        hideAll();
		        content.slideDown(ani.duration, ani.easing, ani.callback);
		      });
		    });
		  };

		})(jQuery)

		//Hide all left nav zippys

		function hideAll() {
		  $(".zippy").each(function(i, el) {
		    var section = $(el).find(".content");
		    section.slideUp(ani.duration, ani.easing, ani.callback);
		  });
		}

		//Toggling the next nav step

		function toggleNextStep() {
		  var elements = $(".zippy");
		  for (var i = 0; i < elements.length; i++) {
		    if ($(elements[i]).find(".content")[0].style.display != "none") {
			    hideAll();
		      if (i < elements.length - 1) { 
		        $(elements[i + 1]).find(".content").slideDown(ani.duration, ani.easing, ani.callback);
		      }
		      break;
		    }
		  }
		}

		//Initializes the button actions

		function initApp() {
		  //using JQuery for Ajax posts
		  document.getElementById("masked").addEventListener("click",

		  function() {
		    $.post("masked-wallet", JSON.stringify(MaskedWalletJson), handleMaskedWalletJwt);
		    display("Requesting Masked Wallet JWT from server");
		  });
		  document.getElementById("full").addEventListener("click",

		  function() {
		    $.post("full-wallet", JSON.stringify(FullWalletJson), handleFullWalletJwt);
		    display("Requesting Full Wallet JWT from server");
		  });

		  $(".right").width($(document).width() - $(".left").width());
		  $(window).resize(function() {
		    $(".right").width($(document).width() - $(".left").width());
		  });
		  $(".zippy").zippify();
		}
		//Wait for dom before initializing buttons
		if (document.readyState === "interactive" || document.readyState === "complete") {
		  initApp();
		}
		// If it"s not ready add an event handler to intialize it when ready
		else {
		  document.addEventListener("DOMContentLoaded", function() {
		    initApp();
		  });
		}
		  </script>
		</head>
		<body>
		  <div id="header_bar">
		    <span id="logo"></span>
		  </div>
		  <div id="subtitle">
		    <span id="subtitle_text">InstantBuy Web Demo</span>
		  </div>
		  <div id="body">
		    <div class="left">
		      <div id="step1" class="zippy">
		        <h2 class="label bg_grey">
		          Step 1 - <span class="dark_grey">Create a Buy button</span>
		        </h2>
		        <div class="content">
		          <p>
		            The first step is generating a Masked Wallet JWT on the server representing the customer information you're requesting and their estimated order total. In this demo we're making an AJAX request for the JWT and using it to create the Google Wallet Buy button.
		          </p><button id="masked" class="kd-button-submit button_size">Create</button>
		        </div>
		      </div>
		      <div id="step2" class="zippy">
		        <h2 class="label bg_grey">
		          Step 2 - <span class="dark_grey">Customer buys the item</span>
		        </h2>
		        <div class="content hidden">
		          <p>
		            Your customer then clicks the Buy button to initiate the purchase. This initiates the selector flow if the user isn't pre-authorized. Google will respond with shipping information and obfuscated shipping information known as the Masked Wallet Response.
		          </p>
		          <div id="wallet-button-holder"></div>
		        </div>
		      </div>
		      <div id="step3" class="zippy">
		        <h2 class="label bg_grey">
		          Step 3 - <span class="dark_grey">Customer confirms purchase</span>
		        </h2>
		        <div class="content hidden">
		          <p>
		            The customer confirms the purchase at which point you request the Full Wallet from Google, charge the order through your processor and notify Google of the Transaction Status.
		          </p><button id="full" class="kd-button-submit button_size" disabled>Confirm</button>
		        </div>
		      </div>
		    </div>
		    <div class="right">
		      <h2 class="label label_sub border_bottom">
		        JWT / Request / Response
		      </h2>
		      <div id="output"></div>
		    </div>
		  </div>
		</body>
		</html>
INDEX;
  }
