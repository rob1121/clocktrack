<div class="modal fade" id="confirmModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body">
          {{ $slot }}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-danger" data-dismiss="modal" id="proceed">
          <span>Proceed</span>
          <i class="fa fa-times-circle"></i>
        </button>
        <button type="button" class="btn btn-primary btn-success" data-dismiss="modal">
          <span>Cancel</span>
          <i class="fa fa-check-circle"></i>
        </button>
      </div>
    </div>
  </div>
</div>
