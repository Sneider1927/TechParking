<div class="modal" id="modal-eliminar-{{  $reg->id }}" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog">
        <div class="modal-content" style="border: none; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
            <form action="{{ route('roles.destroy', $reg->id) }}" method="post">
                @csrf
                @method('DELETE')
                <div style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; padding: 20px; border-radius: 5px 5px 0 0;">
                    <h4 style="margin: 0; display: flex; align-items: center; gap: 10px; font-size: 18px; font-weight: bold;">
                        <i class="bi bi-exclamation-triangle" style="font-size: 24px;"></i>
                        Confirmar Eliminación
                    </h4>
                </div>

                <div class="modal-body" style="padding: 25px;">
                    <div style="background: #fff3cd; border-left: 4px solid #f39c12; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
                        <p style="margin: 0; color: #856404;"><strong><i class="bi bi-info-circle"></i> Advertencia:</strong></p>
                    </div>
                    <p style="margin: 0; color: #34495e; font-size: 16px;">
                        ¿Está seguro de que desea eliminar el rol <strong style="color: #e74c3c;">{{ ucfirst($reg->name) }}</strong>? 
                        Esta acción no se puede deshacer.
                    </p>
                </div>

                <div class="modal-footer" style="border-top: 1px solid #ecf0f1;">
                    <button type="button" class="btn" data-bs-dismiss="modal" style="background: #95a5a6; color: white; border: none; border-radius: 5px; padding: 8px 16px;">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none; border-radius: 5px; padding: 8px 16px;">
                        <i class="bi bi-trash-fill"></i> Eliminar
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
