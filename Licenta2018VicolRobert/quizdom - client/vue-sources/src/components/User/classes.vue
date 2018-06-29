<template>
  <div>
  <div class="card">
    <h3>Before you start playing multiplayer, you must choose a class. This will be bound to your account. The class serves the purpose of giving you some unique abilities that you can use to your advantage during your matches.</h3>
    <h3>Gaining victories and playing more matches will earn you XP points, which level up your "character". The higher the level, the stronger the abilities you have are and the more advantageous for you (or disadvantageous for your opponent)</h3>
    <div class="crs">
    <carousel-3d  class="carusel" :width="500" height="500">
  <slide class="cchild" :index="0">
    <h2 class="crstitle">The Trickster</h2>
    <img src="../../cheater.gif" alt="Trickster">
    <h3>Level 1 ability</h3>
    <p>Removes one choice from the opponents problem. Can only be used once per turn.</p>
    <h3>Level 10 ability</h3>
    <p>Can steal the points gained by the opponent in one turn. Once per turn.</p>
  </slide>
  <slide class="cchild" :index="1">
    <h2 class="crstitle">The TimeLord</h2>
    <img src="../../time.gif" alt="TimeLord">
    <h3>Level 1 ability</h3>
    <p>Extends the time available to answer one problem by 10 seconds, or shortens the time available to your opponent. Can only be used once per game. </p>
    <h3>Level 10 ability</h3>
    <p>Can replay one turn. Once per game</p>
  </slide>
    <slide class="cchild" :index="2">
    <h2 class="crstitle">The Master of Odds</h2>
    <img src="../../deuces.gif" alt="Master of Odds">
    <h3>Level 1 ability</h3>
    <p>Removes two wrong choices from the problem, If that problem has a total of 4 choices. Once per turn</p>
    <h3>Level 10 ability</h3>
    <p>Play the odds. Let the random generator pick for you, with an increased chance to obtain the right answer. Once per turn</p>
  </slide>
    <slide class="cchild" :index="3">
    <h2 class="crstitle">The Nullifier</h2>
    <img src="../../nullifier.gif" alt="Trickster" class="trickster">
    <h3>Level 1 ability</h3>
    <p>Nullifies opponent's level 1 ability. Can be used 5 turns.</p>
    <h3>Level 10 ability</h3>
    <p>Nullifies opponent's level 10 ability. Can be used 5 turns.</p>
  </slide>
      <slide class="cchild" :index="4">
    <h2 class="crstitle">The CopyCat</h2>
    <img src="../../copy.gif" alt="Trickster">
    <h3>Level 1 ability</h3>
    <p>Gains opponent's level 1 ability. Can be used once.</p>
    <h3>Level 10 ability</h3>
    <p>Gains opponent's level 10 ability. Can be used once.</p>
  </slide>
</carousel-3d>
    </div>
  </div>
  </div>
</template>

<script>
  import Vue from 'vue'

  export default {
    name: 'classes',
    data () {
      //var user = JSON.parse(window.localStorage.getItem('USER'))
      return {
        categories:[],
        categorywanted:0,
        problems:[],
        chosen:0
      }
    },

    methods: {
      select(id)
      {
        this.chosen=id;
      },
      showproblems(id)
      {
        this.categorywanted = id;
      }

  },
  mounted() {

  }
}
</script>


<style scoped>
  .cchild{
    text-align: center;
    border:solid 1px darkred;
    border-radius: 5%;
  }
  .cchild p{
    padding-left:2rem;
    padding-right:2rem;
  }
  .crs{
        border:solid 1px darkred;
    border-radius: 2%;
  }

  .cchild img{
    width:200px;
    border:solid 1px darkred;
    height:auto;
    border-radius: 5%;
    background: white;
  }
  .crstitle{
    margin-top:1rem;
    text-align:center;

  }
  .carusel{
    width:98%;
  }
</style>
