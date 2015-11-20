<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project extends CI_Controller {
	
	public function index()
	{
		$this->template->view('project/dashboard_view');
	}
	
	public function new_address_card()
	{
		$this->template->view('project/new_address_card_view');	
	}
	
	public function list_address_card()
	{
		$this->template->view('project/list_address_card_view');	
	}
	
	public function get_address_card($account_id)
	{
		$data['account_id'] = $account_id;
		$this->template->view('project/get_address_card_view', $data);	
	}
	
	public function add_project()
	{
		$this->template->view('project/add_project_view');	
	}
	
	public function list_project()
	{
		$this->template->view('project/list_project_card_view');	
	}
	
	public function get_project($project_id)
	{
		$data['project_id'] = $project_id;
		$this->template->view('project/get_project_view', $data);
	}
	
	public function menu_project()
	{
		$this->template->view('project/menu_project_view');	
	}
	
	public function menu_location()
	{
		$this->template->view('project/menu_location_view');	
	}
	
	public function job_description()
	{
		$this->template->view('project/job_description_view');
	}	
	
	
	// work order
	public function menu_work_order()
	{
		$this->template->view('project/menu_order_view');
	}	
	
	public function new_work_order()
	{
		$this->template->view('project/new_work_order_view');	
	}
	
	public function list_work_orders()
	{
		$this->template->view('project/list_work_orders_view');	
	}
	
	public function get_work_order($work_order_id)
	{
		$data['work_order_id'] = $work_order_id;
		$this->template->view('project/get_work_order_view', $data);	
	}
	
	public function ajax_get_project_item($project_id)
	{
		$this->db->where('project_id', $project_id);
		$this->db->where('status', 1);
		$query = $this->db->get('p_project_jobs')->result_array();
		?>
        <label for="project_job_id" class="control-label ff-1 fs-16"><?php lang('Job Description'); ?></label>
        <select name="project_job_id" id="project_job_id" class="form-control input-lg fs-16 ff-1">
        	<?php foreach($query as $q): ?>
            	<option value="<?php echo $q['id']; ?>"><?php echo $q['name']; ?></option>
            <?php endforeach; ?>
        </select>
        <?php
	}
	
	
	public function map_creation()
	{
		$this->template->view('project/map_creation_view');	
	}
	
	public function map_creation_show()
	{
		$this->load->view('project/map_creation_show_view');	
	}
	
	public function get_product_serial($code)
	{
		$this->db->where('code', $code);
		$query = $this->db->get('products')->row_array();
		
		if($query)
		{
			?>

         
            

            
            <script>
			$(document).ready(function(e) {
                /* datatable */
				$('.dataTable_erer').dataTable({
					"sDom": "<'row'<'col-md-12'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>"
				});
            });
			</script>
            
            <!-- Button trigger modal -->
            <a data-toggle="modal" href="#myModal_product_serial_list" id="modal-product_serial_list"></a>
            
            <!-- Modal -->
            <div class="modal fade" id="myModal_product_serial_list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h4 class="modal-title"><?php echo $query['name']; ?> Seri Numaraları</h4>
                    </div>
                    <div class="modal-body">
                        <?php 
						$this->db->where('status', 1);
						$this->db->where('product_id', $query['id']);
						$this->db->where('invoice_id', '0');
						$serials = $this->db->get('product_serials')->result_array();
						?>
						<table class="table table-bordered table-hover table-condensed dataTable_erer">
							<thead>
								<tr>
									<th></th>
									<th><?php lang('Serial Number'); ?></th>
								</tr>
							</thead>
							<tbody>
						<?php foreach($serials as $serial): ?>
							<tr>
								<td width="1">
                                	<a href="javascript:;" style="height:20px; padding:3px;" class="btn btn-sm btn-default btnSelected_serial" 
                                        data-serial_id='<?php echo $serial['id']; ?>' 
                                        data-serial_serial='<?php echo $serial['serial']; ?>'
                                        >
                                    <?php lang('Choose'); ?></a>
                				</td>
								<td><?php echo $serial['serial']; ?></td>
							</tr>
						<?php endforeach; ?>
							</tbody>
						</table>
                        
                        
                        	<script>
							$('.btnSelected_serial').click(function() {
								$('#serial').val($(this).attr('data-serial_serial'));
								$('.close').click();
							});
						</script>
                        
                    </div>
                    
                  </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            
            <?php	
		}
		else
		{
			
		}
	}
	
	function excel_new_locations()
	{
		
		$this->template->view('project/excel_new_locations_view');	
	}
	
	
	public function show_photos()
	{
		$this->template->view('project/show_photos_view');	
	}
	
}
