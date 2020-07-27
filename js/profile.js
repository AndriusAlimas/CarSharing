// Ajax call to updateusername.php
$("#updateUsernameForm").submit(function(event){
    // prevent default php processing
    event.preventDefault();

    // collect user input
    var datapost = $(this).serializeArray();

    // send them to updateusername.php using AJAX
    $.ajax({
        url: "updateusername.php",
        type: "POST",
        data: datapost,

        // AJAX Call successful: show error or success message
        success: function(data){
            if(data){
                $("#updateUsernameMessage").html(data);
            }else{
                location.reload();
            }
        },
        error: function(){
            // AJAX Call fails: show Ajax Call error
            $("#updateUsernameMessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call.Please try again later.</div>");
        }
    });
});

// Ajax call to updatepassword.php
$("#updatePasswordForm").submit(function(event){
    // prevent default php processing
    event.preventDefault();

    // collect user input
    var datapost = $(this).serializeArray();

    // send them to updatepassword.php using AJAX
    $.ajax({
        url: "updatepassword.php",
        type: "POST",
        data: datapost,

        // AJAX Call successful: show error or success message
        success: function(data){
            if(data){
                $("#updatePasswordMessage").html(data);
            }
        },
        error: function(){
            // AJAX Call fails: show Ajax Call error
            $("#updatePasswordMessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call.Please try again later.</div>");
        }
    });
});

// Ajax call to updateemail.php
$("#updateEmailForm").submit(function(event){
    // prevent default php processing
    event.preventDefault();

    // collect user input
    var datapost = $(this).serializeArray();

    // send them to updateemail.php using AJAX
    $.ajax({
        url: "updateemail.php",
        type: "POST",
        data: datapost,

        // AJAX Call successful: show error or success message
        success: function(data){
            if(data){
                $("#updateEmailMessage").html(data);
            }
        },
        error: function(){
            // AJAX Call fails: show Ajax Call error
            $("#updateEmailMessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call.Please try again later.</div>");
        }
    });
});

// update profile preview
var file, imageType, imageSize, wrongType;
$("#picture").change(function(){
	file = this.files[0];
	
	imageType = file.type;
	imageSize = file.size;
	
	// check acceptable image types in array with user input type
	var acceptableTypes = ["image/jpeg","image/png","image/jpg"];
	wrongType = ($.inArray(imageType,acceptableTypes) == -1);
	
	if(wrongType){
		$("#updatePictureMessage").addClass("alert alert-danger");	
		$("#updatePictureMessage").html("Only <strong>jpeg</strong>, <strong>png</strong> and <strong>jpg</strong> images are accepted!");
				return false;
	}
	
	// check acceptable image size in array with uploaded file size
		if(imageSize > 3 * 1024 * 1024){ // 3 * 1024 * 1024 = 3MB
				$("#updatePictureMessage").addClass("alert alert-danger");
				$("#updatePictureMessage").html("Please upload an image less than <strong>3MB!</strong>");
					return false;	
	}
	
	// The FileReader object will be used to convert our image to a binary string
	var reader = new FileReader();
	
	// callback
	reader.onload = updatePreview;
	
	// Start the read operation -> convert content into a data URL which is passed to the callback
	reader.readAsDataURL(file);
	
	$("#updatePictureMessage").attr("class", 'alert alert-success');
	$("#updatePictureMessage").html("You can upload this picture.");
});

// update picture
$("#updatePictureForm").submit(function(){
	event.preventDefault();
	
	// file is missing
	if(!file){
		$("#updatePictureMessage").addClass("alert alert-danger");
		$("#updatePictureMessage").html("Please upload the picture!");
		return false;
	}
	
	// wrong type
	if(wrongType){
			$("#updatePictureMessage").addClass("alert alert-danger");	
			$("#updatePictureMessage").html("Only <strong>jpeg</strong>, <strong>png</strong> and <strong>jpg</strong> images are accepted!");
				return false;
	}
	
	// file too big
	if(imageSize > 3 * 1024 * 1024){ // 3 * 1024 * 1024 = 3MB
			$("#updatePictureMessage").addClass("alert alert-danger");
			$("#updatePictureMessage").html("Please upload an image less than <strong>3MB!</strong>");
				return false;	
	}
	
	// send Ajax Call to updatepicture.php 
	$.ajax({
        url: "updatepicture.php",
        type: "POST",
        data: new FormData(this) ,
	
                contentType: false,       // The content type used when sending data to the server.
                cache: false,             // To unable request pages to be cached
                processData:false,        // To send DOMDocument or non processed data file it is set to false
		
        // AJAX Call successful: show error or success message
        success: function(data){
          if(data){
					$("#updatePictureMessage").html(data);
					}else
					{
						location.reload();
					}
        },
		
        error: function(){
            // AJAX Call fails: show Ajax Call error
            $("#updatePictureMessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call.Please try again later.</div>");
        }
    });
	
});

// FUNCTIONS
// this is callback function if successfully uploaded file we want update html code change attribute from src to url immage location
function updatePreview(event){
//	console.log(event);
	$("#profilePicturePreview").attr("src",event.target.result);
}