<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$tid = 10630;
if (isset($_POST['saveSignature'])) {
    $name = $file = "";
    if (isset($_POST['name'])) {
        $name = $_POST['name'];
    }


    $folderPath = "signatures/";
    $image_parts = explode(":base64", $_POST["signatureImage"]);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = 'jpg';

    $image_base64 = base64_decode($image_parts[1]);
    echo 'type: ' . $image_type;
    $file = $folderPath . $name . "_" . uniqid() . '.' . $image_type;

    file_put_contents($file, $image_base64);
    // echo "Signature Uploaded Successfully."; 
    // $tenant->saveSignature($tid,$name,$image_base64,$image_type);
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How to create signature pad in html</title>
    <!-- <link rel="stylesheet" href="../css/signature-pad.css"> -->
    <style>
        @font-face {
            font-family: 'blenheim-signature';
            src: url('./fonts/blenheim-signature-font/Blenheim Signature v2.ttf');
            /* Add more font formats if needed */
        }
    </style>
</head>

<body>
    <form action="signatureComponent.php" method="post" onload="(event)=>event.preventDefault()" style="width: 450px; border:solid 1px black;margin:auto;">
        <div style="margin:auto; padding: 22px;">
            <div style="display: flex; flex-direction: row; margin:10px auto;">
                <label>
                    <input type="radio" id="sign-method1" onclick="toggleEdit(event)" name="signature-method" value="type">
                    Type
                </label>
                <label>
                    <input type="radio" id="sign-method2" onclick="toggleEdit(event)" name="signature-method" value="draw">
                    Draw
                </label>
            </div>
            <div class="flex-col">
                <div class="wrapper">
                    <div id="type-initials-container" style="display: flex; flex-direction: row; margin:10px auto;">
                        <label id="initials-label" for="type-initials">
                            Initials
                            <input id="type-initials" name="initials" type="text" value="" onkeyup="drawText(event)" />
                        </label>
                    </div>
                    <div style="display: flex; flex-direction: row; margin:10px auto;">
                        <canvas id="signature-pad" width="400" height="200" style="border:solid 1px black;"></canvas>
                    </div>
                    <textarea id="signatureImg" name="signatureImage" style="display:none;"></textarea>
                </div>
                <div class="clear-btn">
                    <button id="clear">Clear</button>
                    <button id="save" name="saveSignature" type=submit>Save</button>
                </div>
            </div>
        </div>
    </form>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.3.5/signature_pad.min.js" integrity="sha512-kw/nRM/BMR2XGArXnOoxKOO5VBHLdITAW00aG8qK4zBzcLVZ4nzg7/oYCaoiwc8U9zrnsO9UHqpyljJ8+iqYiQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var canvas = document.getElementById("signature-pad");
    var ctx = canvas.getContext("2d");

    document.getElementById('sign-method1').checked = true;
    canvas.style.pointerEvents = 'none';

    function drawText(e) {
        var text = e.target.value;
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.font = 'bold 30px blenheim-signature';
        ctx.fillStyle = "black";
        ctx.fillText(text, 10, 110);
    }

    function resizeCanvas() {
        var ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
    }
    window.onresize = resizeCanvas;
    resizeCanvas();

    var signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255,255,255)'
    });

    document.getElementById("clear").addEventListener('click', function() {
        signaturePad.clear();
        drawText();
    });
    document.getElementById("signature-pad").addEventListener('mouseup', function() {
        const sigImage = signaturePad.toDataURL("image/jpg").split(':base64');
        console.log(sigImage[0])
        document.getElementById("signatureImg").value = sigImage;
    });

    function toggleEdit(e) {
        method = e.target.value;
        if (method !== "type") {
            document.getElementById('type-initials-container').style.display = "none";
            canvas.style.pointerEvents = 'all';

        } else {
            document.getElementById('type-initials-container').style.display = "block";
            canvas.style.pointerEvents = 'none';
        }
        signaturePad.clear();
        document.getElementById("type-initials").value = "";
        console.log("method", e.target.value)
    };

    // document.addEventListener('load',()=>{

    // })
</script>

</html>