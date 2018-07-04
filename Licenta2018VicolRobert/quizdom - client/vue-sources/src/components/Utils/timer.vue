<template>
  <div id="app">
      <p v-bind:style="{'color':color}">
        {{(hours!=0 ? hours+":" : "") + (minutes!=0 ? minutes+":" : "") + (Math.abs(seconds)<10 ?       seconds:seconds) }}
        <!--                                                                                    "0" + -->
      </p>
  </div>
</template>
<script>
  export default {
    name: 'timer',
    data () {
      return {
        setI:0,
        count:0
      }
    },
    props: {
      interval: {
        type:Number,
        default:1000
      },
      time:{
        type:Number,
        default:20
      },
      stop:{
        type:Boolean,
        default:false
      },
      color:{
        default:"white"
      },
    },
    watch:{
      stop: function() {
        if(this.stop)
        {
          clearInterval(this.setI)
          var timeobj = {"hours":this.hours,"minutes":this.minutes,"seconds":this.seconds}
//          console.log(timeobj)
          this.$emit("timeleft",timeobj)
        }
        else{
          this.startTimer()
        }
      }
    },
    computed:{
      hours:function(){
        return Math.floor(this.count / 3600000) //1000*60*60
      },
      minutes:function() {
        return Math.floor(this.count / 60000) % 60 //1000*60 % 60
      },
      seconds:function(){
        var x = this.count / 1000
        var decimals = x - Math.floor(x);

        var arr = x.toString().split('.')
        if(arr.length === 2)
        {
          var decimalPlaces = x.toString().split('.')[1].length;
          decimals = decimals.toFixed(decimalPlaces);
//          console.log(Math.floor(x) % 60)
//          console.log(parseFloat(decimals))
          return (Math.floor(x) % 60) + parseFloat(decimals)

        }
        else
          if (this.interval % 1000 === 0)
          {
            return Math.floor(x) % 60
          }
          else
            return (Math.floor(x) % 60).toFixed(1)



      }
    },
    methods: {
      stopTimer()
      {

      },
      startTimer(){
        this.count = this.time * 1000
        this.setI = setInterval(() => {
          this.count -= this.interval
          if(this.count<=0)
          {
            this.count = 0
            clearInterval(this.setI)
            var timeobj = {"hours":0,"minutes":0,"seconds":0}
//            console.log(timeobj)
            this.$emit("timeleft",timeobj)
          }
        },this.interval)
      }
    },
    mounted() {
      this.startTimer()
    }
  }
</script>
<style scoped>
p {
  margin-bottom:0
}
</style>
