<!-- Data layer code -->
<script>
 <?php if(isset(Yii::app()->user->id) && isset(Yii::app()->user->role)):?>
    dataLayer = [{
      'userID': '<?php echo Yii::app()->user->id;?>', //ID User
      'userRole': '<?php echo Yii::app()->user->role;?>',// Role User
 	  'visitorType': 'loggedUser'
    }];
  <?php else:?>
  	dataLayer = [{
      'userID': '0', //ID User
      'userRole': 'none',// Role User
 	  'visitorType': 'UnloggedUser'
    }];  
  <?php endif;?>
</script>
<!-- End data layer code -->