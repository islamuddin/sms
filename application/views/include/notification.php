
<ul class="nofitications-dropdown">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" id="view_notify"><i class="fa fa-bell-o"></i><span class="badge blue notif"></span></a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <div class="notification_header">
                                                <h3>You have <span class=""></span> new notifications</h3>
                                            </div>
                                        </li>
            
                                   
                                        <li class="odd">
                                            <span href="#" class="vname">
                                               
                                            </span>
                                        </li>
                                        <li>
                                            <div class="notification_bottom">
                                                <a href="<?php echo base_url('records/all'); ?>" class="bg-primary">See all notifications</a>
                                            </div>
                                        </li>
                                       
                                       
                                    </ul>
                                </li>


                               
                            </ul>
                            
                            
<script>
 
 $(document).ready(function(){
  
  //updating the view with notifications using ajax
 
 function load_unseen_notification(view = '')
  
 {
 


}

load_unseen_notification();
 
 $('#view_notify').on('click', function(){
 
 $('.notif').html('');
 
 load_unseen_notification('yes');
 
});
 
// setInterval(function(){
 
 load_unseen_notification();
 
// }, 5000);
 
});
</script>
