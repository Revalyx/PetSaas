<div id="deleteModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 backdrop-blur-sm">

    <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-200 dark:border-gray-700">

        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
            Confirmar Eliminación
        </h2>

        <p id="deleteMessage" class="text-gray-600 dark:text-gray-300 text-sm mb-6"></p>

        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')

            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-700 hover:opacity-80">
                    Cancelar
                </button>

                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white shadow">
                    Eliminar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDeleteModal(id, name) {
        const modal = document.getElementById('deleteModal');
        const form  = document.getElementById('deleteForm');
        const msg   = document.getElementById('deleteMessage');

        form.action = '/superadmin/tenants/' + id;
        msg.innerHTML =
            `Va a eliminar <strong>${name}</strong>.<br><br>
             Se eliminarán:<br>
             • Su base de datos<br>
             • Usuarios asociados<br>
             • Registro en PetSaaS<br><br>
             <strong>Esta acción no se puede deshacer.</strong>`;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
