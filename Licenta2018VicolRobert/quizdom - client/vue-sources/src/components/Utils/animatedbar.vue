<template>
  <div id="app">
    <div v-bind:style="{'background':color}" :id="'bar'+uid"></div>
  </div>
</template>
<script>
  export default {
    name: 'animateBar',
    data () {
      return {
//        width_:0,
//        height_:0,
//        dif:0
      }
    },
    props: {
      uid:{
        default:Math.random()
      },
      width: {
        default:100
      },
      height:{
        default:5
      },
      time:{
        default:100
      },
      color:{
        default:"blue"
      },
//      breakdistance:{
//        default:10
//      },

      animateDimension:{
        type:String,
        validator: value => {
          return (value=="width" || value=="height")
        }
      },
      endValue:{
        validator : value => {
          return (value>=0)
        }
      }
      // animateDirection:{
      //   type:String,
      //   validator: value => {
      //     return (value=="left" || value=="right" || value=="middle")
      //   }
      // }

    },
    watch:{
//      hours:function(){
//        return Math.floor(this.count / 3600000) //1000*60*60
//      },
      width:function() {
        if (this.animateDimension==="width") {
          console.log(this.uid)
          var el = document.getElementById("bar"+this.uid);
          // this.dif = this.width_ - this.width
          if (this.endValue <= this.width) {
//            this.width_ = this.width
            el.style.width = this.width + "px"
          }
//          console.log(this.width)
        }
      },
      height:function() {
        if (this.animateDimension==="height")
        {
          var el =  document.getElementById("bar"+this.uid);
          // this.dif = this.width_ - this.width
          if(this.endValue <= this.height)
          {
//            this.height_ = this.height
            el.style.height = this.height + "px"
          }
//          console.log(this.height)
        }
        }
    },
    methods: {
      init(){
        var el =  document.getElementById("bar"+this.uid);
//        this.width_ = this.width
//        this.height_ = this.height
        el.style.transition = "all "+ this.time +"s linear"
        el.style.width = this.width + "px"
        el.style.height = this.height + "px"
      }
    },
    mounted() {
      this.init()
    }
  }
</script>
<style>


</style>
