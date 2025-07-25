export function renderRestaurantes(restaurantes, onEdit, onDelete) {
  const list = document.getElementById('restaurantes-list');
  list.innerHTML = '';

  restaurantes.forEach(r => {
    const li = document.createElement('li');
    li.textContent = `${r.nombre} - ${r.direccion} - ${r.telefono}`;

    const editBtn = document.createElement('button');
    editBtn.textContent = 'Editar';
    editBtn.className = 'edit';
    editBtn.onclick = () => onEdit(r);

    const deleteBtn = document.createElement('button');
    deleteBtn.textContent = 'Eliminar';
    deleteBtn.className = 'delete';
    deleteBtn.onclick = () => onDelete(r.id);

    li.appendChild(editBtn);
    li.appendChild(deleteBtn);
    list.appendChild(li);
  });
}

export function fillForm(restaurante) {
  document.getElementById('restaurante-id').value = restaurante.id;
  document.getElementById('nombre').value = restaurante.nombre;
  document.getElementById('direccion').value = restaurante.direccion;
  document.getElementById('telefono').value = restaurante.telefono;
}

export function clearForm() {
  document.getElementById('restaurante-id').value = '';
  document.getElementById('nombre').value = '';
  document.getElementById('direccion').value = '';
  document.getElementById('telefono').value = '';
}