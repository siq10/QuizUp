<template>
  <div>
    <div class="card">
      <p>Daca aveti deja un cont, va puteti autentifica cu userul si parola folosind butonul de mai jos.</p>
      <router-link :to="'login'" class="button">Login</router-link>
    </div>
    <div class="card">
      <p>Daca nu aveti cont, va puteti crea unul folosind acest buton.</p>
      <router-link :to="'register'" class="button">Create</router-link>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'hello'
  }
</script>
