@extends('admin.layouts.main')

@section('content')
<section class="content">
    <div class="container-fluid">
       <div class="row"> 
        <div class="card">
        <form id="settingForm" method="post" action="{{ route('admin.general_settings.update') }}" enctype="multipart/form-data">
            @csrf    
            <div class="card-header p-2 bg-transparent">
                <ul class="nav nav-pills" id="pills-tab" role="tablist">
                <?php  
                foreach ($parent_setting['type'] as $key => $value) {
                  if($key==1){
                    $istrue = 'true';
                    $isactive = 'active';
                  }else{
                    $istrue = 'false';
                    $isactive = '';   
                  }
                 ?>
                  <li class="nav-item">
                    <a class="nav-link <?php echo $isactive; ?>" id="pills-home-tab" data-toggle="pill" href="#<?php echo $value; ?>" role="tab" aria-controls="<?php echo $value; ?>" aria-selected="<?php echo $istrue; ?>"><?php echo $parent_setting['name'][$key]; ?></a>
                  </li> 
                 <?php } ?>  
                </ul>
            </div>
            <div class="card-body">
                <!-- Tab panes -->
                <div class="tab-content">
                  <?php  
                  foreach ($parent_setting['type'] as $key => $value) {
                    if($key==1){
                      $istrue = 'true';
                      $isactive = 'active';
                    }else{
                      $istrue = 'false';
                      $isactive = '';   
                    }
                   ?> 
                    <div role="tabpanel" class="tab-pane <?php echo $isactive; ?>" id="<?php echo $value; ?>"> 
                      <div class="row">
                      <?php foreach ($general_settings[$key] as $skey => $svalue) { 
                        switch ($svalue['filed_type']) {
                        case "file":  ?>
                        <div class="col-sm-6">
                          <div class="form-group">
                              <label class="control-label"><?php echo $svalue['filed_label']; ?></label><br/>
                              <?php if(!empty($svalue['filed_name'])): ?>
                                 <p><img src="{{asset($svalue['filed_value'])}}" class="<?php echo $svalue['filed_name'] ?> <?php echo $svalue['filed_name'].'smallimg' ?>"></p>
                             <?php endif; ?>
                             <input type="file" name="<?php echo $svalue['filed_name'].'_'.$svalue['id']; ?>" >
                             <p><small class="text-success">Allowed Types: gif, jpg, png, jpeg</small></p>
                             <input type="hidden" name="SettingOld[filedval][<?php echo $svalue['id'] ?>] ?>" value="<?php echo html_escape($svalue['filed_value']); ?>">
                          </div>
                        </div>
                      <?php  
                        break;
                        case "text": ?> 
                          <div class="col-sm-6">
                          <div class="form-group">
                            <label class="control-label"><?php echo $svalue['filed_label']; ?></label>
                            <input type="text" class="form-control" name="Setting[filedval][<?php echo $svalue['id'] ?>]" placeholder="<?php echo $svalue['filed_label']; ?>" value="<?php echo html_escape($svalue['filed_value']); ?>">
                          
                          <?php 
                          if($svalue['filed_name']=='timezone'){
                            echo '<small class="text-success">if system get wrong timezone then system will set default timezone(UTC)</small><br><a href="http://php.net/manual/en/timezones.php" target="_blank">Get Timeszone List</a>';
                          }
                          ?></div>
                          </div>
                       <?php  
                        break;
                        case "number": ?> 
                          <div class="col-sm-6">
                          <div class="form-group">
                            <label class="control-label"><?php echo $svalue['filed_label']; ?></label>
                            <input type="text" class="form-control" name="Setting[filedval][<?php echo $svalue['id'] ?>]" placeholder="<?php echo $svalue['filed_label']; ?>" value="<?php echo html_escape($svalue['filed_value']); ?>"  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g, '')" >
                           </div>
                          </div>
                       <?php  
                        break;
                        case "color": ?> 
                          <div class="col-sm-6">
                          <div class="form-group">
                            <label class="control-label"><?php echo $svalue['filed_label']; ?></label>
                            <input type="color" class="form-control" name="Setting[filedval][<?php echo $svalue['id'] ?>]" placeholder="<?php echo $svalue['filed_label']; ?>" value="<?php echo html_escape($svalue['filed_value']); ?>">
                          </div>
                          <?php 
                          if($svalue['filed_name']=='timezone'){
                            echo '<a href="http://php.net/manual/en/timezones.php" target="_blank">Timeszones</a>';
                          }
                          ?>
                          </div>    
                      <?php  
                        break;
                        case "password":
                        ?>
                          <div class="col-sm-6">
                          <div class="form-group">
                            <label class="control-label"><?php echo $svalue['filed_label']; ?></label>
                            <input type="password" class="form-control" name="Setting[filedval][<?php echo $svalue['id'] ?>]" placeholder="<?php echo $svalue['filed_label']; ?>" value="<?php echo html_escape($svalue['filed_value']); ?>">
                          </div>
                          </div>
                    <?php  
                        break;
                        case "textarea":
                        ?>
                          <div class="col-sm-6">
                          <div class="form-group">
                            <label class="control-label"><?php echo $svalue['filed_label']; ?></label>
                            <textarea class="form-control" name="Setting[filedval][<?php echo $svalue['id'] ?>]" placeholder="<?php echo $svalue['filed_label'];?>" ><?php echo html_escape($svalue['filed_value']); ?></textarea> 
                          </div>      
                          </div>
                           <?php  
                        break;
                        case "select":
                        ?>
                          <div class="col-sm-6">
                          <div class="form-group">
                            <label class="control-label"><?php echo $svalue['filed_label']; ?></label>
                            <select  class="form-control" name="Setting[filedval][<?php echo $svalue['id'] ?>]">
                              <option <?php if($svalue['filed_value']==1){ echo"selected";} ?> value="1">True</option>
                              <option <?php if($svalue['filed_value']==0){ echo"selected";} ?> value="0">False</option>
                            </select> 
                          </div>      
                          </div>
                    <?php } 
                      } ?>  
                    </div>
                    </div>
                  <?php } ?>   

                </div> 
                <div class="box-footer">
                    <input type="submit" name="submit" value="Save Changes" class="btn btn-primary pull-right">
                </div>  
            </div>
        </form>
        </div>
        </div>
    </div>
</section>
@endsection
