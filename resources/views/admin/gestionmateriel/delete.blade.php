<div id="delete_appointment{{$typemateriel->id}}" class="modal fade delete-modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="{{asset('assets/img/sent.png')}}" alt="" width="50" height="46">
                <h3>ETES-VOUS SUR DE VOULOIR SUPPRIMER CET TYPE DE MATERIEL ?</h3>
                <form id="delete-form" action="{{ route('gestiondemarque.destroy', $marque->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="m-t-20">
                        <a href="#" class="btn btn-white" data-dismiss="modal">Annuler</a>
                        <button type="submit" class="btn btn-danger">Effacer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

