 <!-- .modal -->
 <div id="confirmation_modal" class="modal fade modal-popup" data-backdrop="true">
	<div class="modal-dialog" id="animate">
		<div class="modal-content">
			<div class="modal-header"><h5 class="modal-title">Confirmation for&nbsp;<span id="confirmation_modal_title"></span></h5></div>
			<div class="modal-body text-center p-lg">
				<p><span id="confirmation_modal_msg">Are you sure you want to delete?</span></p>
			</div>
			<div class="modal-footer">				
				<a href="javascript:void(0);" class="btn btn-sm btn-success" data-dismiss="modal" onClick="javascript:popupSubmtBtnAction();"><i class="material-icons">&#xe876;</i>Yes</a>
				<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="material-icons">&#xe5CD;</i>No</button>
				<input type="hidden" id="confirmation_modal_frmId" value="">
			</div>
		</div>
	</div>
 </div>
<!-- / .modal -->