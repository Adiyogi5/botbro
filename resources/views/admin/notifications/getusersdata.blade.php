<div class="modal-content">
  <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">{{$title}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
  <ul>
    <?php 
    $users  = json_decode($records->users);
    foreach($users as $key => $value){ ?>
    <li><?php echo $value->name.' ('.$value->mobile.')'; ?></li>
   <?php } ?>
  </ul>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
  </div>
</div>