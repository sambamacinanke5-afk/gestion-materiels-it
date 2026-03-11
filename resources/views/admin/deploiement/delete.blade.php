<div id="delete_deploiement{{ $deploiement->id }}" class="modal fade delete-modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="{{ asset('assets/img/sent.png') }}" alt="" width="50" height="46">

                <h3>ÊTES-VOUS SÛR DE VOULOIR SUPPRIMER CE DÉPLOIEMENT ?</h3>

                <form action="{{ route('deploiement.destroy', $deploiement->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="mt-3">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Annuler
                        </button>
                        <button type="submit" class="btn btn-danger">
                            Supprimer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
