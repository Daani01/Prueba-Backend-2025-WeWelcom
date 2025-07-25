import * as model from './model.js';
import {
  renderRestaurantes,
  fillForm,
  clearForm
} from './view.js';

let apiKey = localStorage.getItem('apiKey') || '';

document.getElementById('auth-form').addEventListener('submit', (e) => {
  e.preventDefault();
  apiKey = document.getElementById('api-key-input').value.trim();
  localStorage.setItem('apiKey', apiKey);
  model.setApiKey(apiKey);
  cargarLista();
});

async function cargarLista() {
  if (!apiKey) return;
  try {
    const data = await model.getRestaurantes();
    renderRestaurantes(data, handleEdit, handleDelete);
  } catch (err) {
    alert('Error cargando restaurantes: ' + err.message);
  }
}

async function handleFormSubmit(event) {
  event.preventDefault();
  const id = document.getElementById('restaurante-id').value;
  const nombre = document.getElementById('nombre').value;
  const direccion = document.getElementById('direccion').value;
  const telefono = document.getElementById('telefono').value;
  const data = { nombre, direccion, telefono };

  try {
    if (id) {
      await model.updateRestaurante(id, data);
    } else {
      await model.createRestaurante(data);
    }
    clearForm();
    cargarLista();
  } catch (err) {
    alert('Error guardando restaurante: ' + err.message);
  }
}

function handleEdit(restaurante) {
  fillForm(restaurante);
}

async function handleDelete(id) {
  if (confirm('¿Seguro que quieres eliminar este restaurante?')) {
    try {
      await model.deleteRestaurante(id);
      cargarLista();
    } catch (err) {
      alert('Error eliminando restaurante: ' + err.message);
    }
  }
}

document.getElementById('restaurante-form').addEventListener('submit', handleFormSubmit);

// Si ya hay una API key guardada, la aplicamos
if (apiKey) {
  document.getElementById('api-key-input').value = apiKey;
  model.setApiKey(apiKey);
  cargarLista();
}
