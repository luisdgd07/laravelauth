<template>
  <div>
    <form @submit.prevent="editarNota(nota)" v-if="modoEditar">
      <h3>Editar nota</h3>
      <input type="text" class="form-control mb-2" 
        placeholder="Nombre de la nota" v-model="nota.nombre">
      <input type="text" class="form-control mb-2" 
        placeholder="Descripción de la nota" v-model="nota.descripcion">
      <button class="btn btn-warning" type="submit">Editar</button>
      <button class="btn btn-danger" type="submit" 
        @click="cancelarEdicion">Cancelar</button>
    </form>
    <form @submit.prevent="agregar" v-else>
      <h3>Agregar nota</h3>
      <input type="text" class="form-control mb-2" 
        placeholder="Nombre de la nota" v-model="nota.nombre">
      <input type="text" class="form-control mb-2" 
        placeholder="Descripción de la nota" v-model="nota.descripcion">
      <button class="btn btn-primary" type="submit">Agregar</button>
    </form>
    <hr>
    <h3>Lista de notas:</h3>
    <ul class="list-group">
         <li class="list-group-item" 
            v-for="(item, index) in notas" :key="index" >
          <span class="badge badge-primary float-right">
            {{item.updated_at}}
          </span>
          <p>{{item.nombre}}</p>
          <p>{{item.descripcion}}</p>
          <p>
            <button class="btn btn-warning btn-sm" 
                @click="editarFormulario(item)">Editar</button>
            <button class="btn btn-danger btn-sm" 
                @click="eliminarNota(item, index)">Eliminar</button>
          </p>
        </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      notas: [],
      modoEditar: false,
      nota: {nombre: '', descripcion: ''}
    }
  },
  created(){
    axios.get('/notas').then(res=>{
      this.notas = res.data;
    })
  },
  methods:{
    agregar(){
      if(this.nota.nombre.trim() === '' || this.nota.descripcion.trim() === ''){
        alert('Debes completar todos los campos antes de guardar');
        return;
      }
      const notaNueva = this.nota;
      this.nota = {nombre: '', descripcion: ''};    
      axios.post('/notas', notaNueva)
        .then((res) =>{
          const notaServidor = res.data;
          this.notas.push(notaServidor);
        })
    },
    editarFormulario(item){
      this.nota.nombre = item.nombre;
      this.nota.descripcion = item.descripcion;
      this.nota.id = item.id;
      this.modoEditar = true;
    },
    editarNota(nota){
      const params = {nombre: nota.nombre, descripcion: nota.descripcion};
      axios.put(`/notas/${nota.id}`, params)
        .then(res=>{
          this.modoEditar = false;
          const index = this.notas.findIndex(item => item.id === nota.id);
          this.notas[index] = res.data;
        })
    },
    eliminarNota(nota, index){
      const confirmacion = confirm(`Eliminar nota ${nota.nombre}`);
      if(confirmacion){
        axios.delete(`/notas/${nota.id}`)
          .then(()=>{
            this.notas.splice(index, 1);
          })
      }
    },
    cancelarEdicion(){
      this.modoEditar = false;
      this.nota = {nombre: '', descripcion: ''};
    }
  }
}
</script>