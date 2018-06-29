<template>
 <div id="app">
   <div class="facecontainer" v-bind:class="{swap:swapped}">
		<div class="firstface  card">
      <h3 @click="changeface">Profile Page</h3>
      <div class="containerforcarousel">
      <carousel-3d :controlsVisible="true" :display="3" :perspective="100" :width="600" :height="500">
        <slide class="profilcar" :index="0">
          <h2>Account</h2>
           <div class="containerincarousel">
              <h3>Name: {{name}}</h3>
              <h3>Email: {{email}}</h3>
              <h3>Tokens: {{tokens}}</h3>
           </div>
          </slide>
          <slide class="profilcar" :index="1">
            <h2>Subjects of Interest:</h2>
            <div class="containerincarousel">
              <ul>
                <li v-for="a in subjects">{{a.name}}</li>
              </ul>
            </div>
          </slide>
        <slide class="profilcar" :index="2">
          <h2>Multiplayer</h2>
          <div class="containerincarousel">
            <h3>You are not eligible for matchmaking yet!</h3>
          </div>
        </slide>
        </carousel-3d>
      </div>
		</div>
   <div class="secondface  card">
     <h3 class="edittitle" @click="changeface">Edit Page</h3>
     <transition name="faderelative" mode="out-in">
       <form class="editform" @submit.prevent="editUser" v-if="!option" key="editdata">
         <label for="email">
           Email:
         </label>
         <input id="email" type="email" v-model="user.email"/>
         <label for="name">
           Name:
         </label>
         <input  id="name" type="text" v-model="user.name"/>
         <label for="oldpw">
           Old Password:
         </label>
         <input id="oldpw" type="password" v-model="user.oldpassword"/>
         <label for="newpw">
           New Password:
         </label>
         <input id="newpw" type="password" v-model="user.newpassword"/>
         <br>
         <button class="editwhitebtn" type="submit">Edit</button>
       </form>
      <div class="page2" v-else>
             <form class="uploadform" @submit.prevent = "submitPicture" enctype="multipart/form-data" novalidate >
               <h4>Upload Profile Picture</h4>
               <div class="inputcontainer">
                 <label id="imagelabel" for="image">CHOOSE IMAGE<img width="40" height="40" src="../../upload_icon.png">
                 <p class="firstchild" v-if="selected">
                   {{image.name}}
                 </p>
                 <p class="secondchild" v-if="selected">
                   {{image? image.size/1000000 + " MB":"" }}
                 </p>

                 </label>
                 <input id="image" type="file" :name="image"  @change="selected"  accept="image/*" ref="image" class="input-file">
                 <p>
                   {{message}}
                 </p>
               </div>
               <button :disabled="disable==1" :class="{editwhitebtn: !disable}" type="submit">Save</button>
             </form>
            <img id="preview" :src="src" alt="PREVIEW IMAGE" v-if="!uploaded"/>
            <img :src="imgpath" :class="imgshape" class="originalimg" :width="width" :height="height" v-else/>
      </div>
     </transition>

     <div class="firstoption" v-bind:class="!option==0 ? 'roundshape' : 'number12'" @click="option=0"></div>
     <div class="secondoption" v-bind:class="!option==1 ? 'roundshape' : 'number12'" @click="option=1"></div>
    </div>
 </div>
 </div>
</template>


<script>
	import Vue from 'vue'
  import Carousel3d from "../../../node_modules/vue-carousel-3d/src/Carousel3d.vue";

	export default {
    name: 'account',
		data () {
			//var user = JSON.parse(window.localStorage.getItem('USER'))
			return {
				subjects:[],
        image:{
				  name:"",
          size:""
        },
				email:"",

        uploaded:1,
        width:0,
        height:0,
        imgpath:"",
        imgshape:"polygon",

        src:"",

        disable:1,
				name:"",
        option:0,
				tokens:0,
				hr:-1,
        message:"",
        swapped:0,
				clicked:-1,
        formData: new FormData(),
      user:{
				  name:"",
          email:"",
          oldpassword:"",
          newpassword:""
        }
      }
		},

		methods: {
      selected(){
        var self = this
        console.log(this.$refs.image.files)
        this.image = this.$refs.image.files[0]
        if(this.image.size/1000000 > 2)
        {
          this.showMessage("File Size is too large!")
          this.disable = 1
//          document.getElementById('preview').src= ""
          self.src = ""

        }
        else {
          this.disable=0
          this.formData.append("image",this.image)
          var reader = new FileReader();

          reader.onload = function(e) {
            self.src = e.target.result
            self.uploaded = 0
          }

          reader.readAsDataURL(this.image);
          this.showMessage("File is ready to be uploaded!")
        }

      },
      resetForm() {
        this.formData = new FormData()
        this.image={
          name:"",
          size:""
        }
        this.message=""
        this.disable=1
        this.uploaded = 0
      },
      showMessage(message)
      {
        this.message=message
      },
      submitPicture(){
//        console.log(this.formData)
//        for(var x of this.formData.entries())
//        {
//          console.log(x)
//        }
          var self = this
          this.$axios.post(this.$apiurl + 'image/add', this.formData)
      .then(response => {
            console.log(response)
            if(response.data.ok == 1)
            {
              this.showMessage('File successfully uploaded!');
              var img = new Image();
              img.onload = function() {
                var payload = {}
                if(this.width >150 && this.height > 150)
                {
                  if(this.width > this.height)
                  {
                    payload.width = this.width / (this.width / 125)
                    payload.height = this.height / (this.width / 125)
                    payload.class = "polygon"
                  }
                  else
                  if(this.width < this.height)
                  {
                    payload.height = this.height / (this.height / 125)
                    payload.width = this.width / (this.height / 125)
                    payload.class = "polygon"
                  }
                  else
                  {
                    payload.width = this.width / (this.width / 150)
                    payload.height = this.height / (this.width / 150)
                    payload.class = "round"
                  }
                }
                else
                {
                  payload.width = 100
                  payload.height = 100
                  payload.class = "round"
                }
                Vue.http.put(self.$apiurl + 'image/size',payload).then(response => {
                  self.imgclass = response.data.shape
                  self.width = response.data.width
                  self.height = response.data.height
                  self.uploaded = 1
                  self.imgpath = self.$publicurl + response.data.imgpath
                  console.log(response)
                })
              }
              img.src = this.$publicurl + response.data.path

            }
            else {
              this.showMessage(response.data.ok);
            }
          })
      },
      editUser(){
        Vue.http.put(this.$apiurl + 'user/edit',this.user).then((response) => {
          this.$store.dispatch('storeUser', response.body)
          this.name = response.body.name
          this.email= response.body.email
          this.user = {
            name:"",
            email:"",
            oldpassword:"",
            newpassword:""
          }
          this.swapped = !this.swapped
        })
      },
      changeface(){
        this.swapped = !this.swapped
      },
			hov(x)
			{
				this.hr=x
			},
			show(x)
			{
				this.clicked=x
			}
			/*gotoMultiPlayer() {
				this.$router.push({path:'/mp'})
			},*/
		},
		mounted() {
			Vue.http.get(this.$apiurl + 'account').then((response) => {
				this.subjects = response.body.DTO.active
				this.email = response.body.DTO.email
				this.name = response.body.DTO.name
				this.tokens = response.body.DTO.tokens
        this.width = response.body.DTO.imgwidth
        this.imgpath = this.$publicurl + response.body.DTO.imgpath
        this.height = response.body.DTO.imgheight
        this.imgshape = response.body.DTO.imgshape
			})
      this.message = "Select an image to upload!"
    },


	}
</script>




<style scoped>
  .faderelative-enter-active, .faderelative-leave-active {
    transition: opacity .5s;
    width: 100%;
  }
  .faderelative-enter, .faderelative-leave-to{
    opacity: 0
  }
  .input-file{
    display: none;
  }

  .editform{
    border:none;
  }
  .edittitle{
    padding: 0;
    margin:0;
  }
  .page2{
    position:relative;
    text-align:initial;
  }

  #imagelabel{
    color:white;
    font-weight:600;
    font-size: 20px!important;
    width:200px;
    height:200px;
    /*padding:0;*/
    line-height:50px;
    color:rgba(255,255,255,0.8);
    -webkit-border-radius: 10px;
    -moz-border-radius: 10px;
    border-radius: 10px;
    position:relative;
    margin:0 auto;
    /*border:5px dotted white;*/
    /*background: linear-gradient(to right, darkred 70%, rgba(0, 0, 0, 0) 0%);*/
    background: #122029;

    cursor:pointer;
    /*background-image: -webkit-linear-gradient(to right, darkred 1%, #191919 100%);*/
    /*background-image: -moz-linear-gradient(to right, darkred 1%, #191919 100%);*/
    /*background-image: -ms-linear-gradient(to right, darkred 1%, #191919 100%);*/
    /*background-image: -o-linear-gradient(to right, darkred 1%, #191919 100%);*/
    /*background-image: linear-gradient(to right, darkred 1%, #191919 100%);*/

    background-image: -webkit-linear-gradient(to bottom, darkred 1%, #191919 100%);
    background-image: -moz-linear-gradient(to bottom, darkred 1%, #191919 100%);
    background-image: -ms-linear-gradient(to bottom, darkred 1%, #191919 100%);
    background-image: -o-linear-gradient(to bottom, darkred 1%, #191919 100%);
    background-image: linear-gradient(to bottom, darkred 1%, #191919 100%);

    transition: all .2s ease;
    text-shadow: 0 1px 1px darkgrey;
  }
  #imagelabel p{
    font-size:15px;
    font-weight:200;
    position:absolute;
    line-height:20px;
    margin-left:15px;
    text-align: left;
    color:rgba(255,255,255,0.8);
  }

  #imagelabel .firstchild
  {
    top: 50%;
  }

  #imagelabel .secondchild
  {
    top: 80%;
  }
  #imagelabel:hover{
     transform: translateY(-1px);
    box-shadow: inset 0 3px 5px ;

   }
  #imagelabel:active{
     box-shadow: inset 0 3px 5px;
     transform: translateY(1px);
   }
  #imagelabel img{
    position:absolute;
    left:calc(50% - 20px);
    top:25%;
    margin:0;
    padding:0;
    background:rgba(255,255,255,0.8);
    border-radius:10px;
  }
  .swap{
    transform: rotateY(180deg);
  }
  .facecontainer{
    perspective:1000px;
    position:relative;
    transition: 0.6s;
    transform-style: preserve-3d;
    width:90vw;
    height:75vh;
    margin:0 auto;
  }
  .secondface{
    -webkit-backface-visibility: hidden;
    -moz-backface-visibility: hidden;
    -ms-backface-visibility: hidden;
    backface-visibility: hidden;
    transform:rotateY(180deg);
    box-shadow: inset 0 1.5px 10px ;
    top: 0;
    height:75vh;
    padding:20px;
    padding-bottom:30px;
    border:none;


    right: calc((100vw - 100%) / 2);
    background-color:#191919;
    color:white;
  }
  .secondface form{
    background-color:transparent;
  }

  .firstface{
    position:absolute;
    width:100%;
    height:100%;
    top: 0;
    padding:25px;
    -webkit-backface-visibility: hidden;
    -moz-backface-visibility: hidden;
    -ms-backface-visibility: hidden;
    backface-visibility: hidden;
    left: calc((100vw - 100%) / 2);
    transform: rotateY(0deg);
    z-index: 2;

  }
  .containerforcarousel{
    width:50%;

    margin:0 auto;
  }

  .containerincarousel{
    margin-left:10%;
  }
    .profilcar h2{
    margin-top:1rem;
    text-align:center;
  }

    .firstoption,.secondoption{
      display: inline-block;
      width:75px;
      height:75px;
      padding:2rem;
      background:white;
      margin:0 auto;
      box-shadow: inset 2px 1.5px 45px black;
      position:absolute;
      bottom:2px;
      transition: all 0.5s ease;
      cursor:pointer;
    }
    .roundshape{
      -webkit-clip-path: circle(30%);
      clip-path: circle(30%);
    }

    .number12{
      clip-path: circle(45%);

    }
    .firstoption{
      left:20%;
    }
    .secondoption{
      right:20%;
    }

  .uploadform{
    border:none;

  }

  @media only screen and (min-width:700px) {
    .page2{
      margin-top:1rem;
    }
    .polygon {
      border-radius:10px;
    }
    .round{
      border-radius:50%;  /*pt pozele cu width = height*/
    }
    .uploadform{
      width:35vw;
      display: inline-block;
      margin:0 20px;
      border:none;

    }
    #preview{
      font-size:35px;
      width:35vw;
      position: absolute;
      height:100%;
      display: inline-block;
      margin-right:20px;
      margin-left:40px;
      text-align:center;
      vertical-align: middle;
      border-radius: 10px ;
    }

  .originalimg{
    width:35vw;
    position: absolute;
    height:100%;
    display: inline-block;
    margin-right:20px;
    margin-left:40px;
    text-align:center;
    vertical-align: middle;
    border-radius: 10px ;
  }
  }

  @media only screen and (max-width: 700px) {
  #preview{
    display: none;
  }
    .originalimg{
      display: none;
    }
    .firstoption{
      left:10%;
    }
    .secondoption{
      right:10%;
    }
  }

  @media only screen and (max-width:1053px) {
    .containerforcarousel {
      width: 100%;
      margin: 0 auto;
    }
    .facecontainer{
      max-width:100%;
    }
    .secondface{
      right:0;

    }
    .firstface{
      left:0;
    }
    .profilcar{
      min-height:500px!important;
    }

  }
  .editwhitebtn
  {
    background:white!important;
    color:black!important;
  }

</style>
