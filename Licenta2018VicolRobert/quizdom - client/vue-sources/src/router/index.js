import Vue from 'vue'
import Router from 'vue-router'
import createUser from '@/components/Auth/createUser'
import login from '@/components/Auth/login'
import welcome from '@/components/User/welcome'
import showMenu from '@/components/User/showMenu'
import Hello from '@/components/Hello'
import newOrder from '@/components/User/newOrder'
import history from '@/components/User/history'
import places from '@/components/User/places'
import placeMenu from '@/components/User/placeMenu'
import singleMode from '@/components/User/singleMode'
import versusMode from '@/components/User/versusMode'
import playMenu from '@/components/User/playMenu'
import singleGame from '@/components/User/singleGame'
import problemMenu from '@/components/User/problemMenu'
import account from '@/components/User/account'
import shop from '@/components/User/shop'
import qmenu from '@/components/User/qmenu'
import owned from '@/components/User/owned'
import qform from '@/components/User/qform'
import boughtList from '@/components/User/boughtList'
import ownedList from '@/components/User/ownedList'
import classes from '@/components/User/classes'
import quickgame from '@/components/User/quickgame'
import challenge from '@/components/User/challenge'

Vue.use(Router)

function authenticated () {
	var token=window.localStorage.getItem('TOKEN')
	if(token)
		return true
	else
		return false
}

export default new Router({
  routes: [
  	{
      path: '/',
      name: 'Hello',
      component: Hello,
         beforeEnter: (to, from, next) => {
         (!authenticated()) ? next() : next({name: 'welcome'})
       }
    },

    {
    	path: '/register',
    	name: 'createUser',
    	component: createUser
    },

    {
    	path:'/login',
    	name: 'login',
    	component: login,
    	beforeEnter: (to, from, next) => {
        (authenticated()) ? next({name: 'welcome'}) :  next()
      }
    },

    {
     	path:'/welcome',
    	name: 'welcome',
    	component: welcome,
    	beforeEnter: (to, from, next) => {
        (!authenticated()) ? next({name: 'login'}) : next()
      }
    },
    {
      path:'/classes',
      name:'classes',
      component: classes
    },

    {
      path:'/playmodes',
      name:'playMenu',
      component: playMenu
    },

    {
      path:'/problemMenu',
      name:'problemMenu',
      component: problemMenu
    },

    {
      path:'/account',
      name:'account',
      component: account
    },

    {
      path:'/singlegame',
      name:'singleGame',
      component:singleGame
    },

    {
      path: '/sp',
      name: 'singleMode',
      component : singleMode,
    },

    {
      path: '/mp',
      name: 'versusMode',
      component : versusMode,
    },
    {
      path:'/quick',
      name:'quickgame',
      component: quickgame
    },
    {
      path:'/challenge',
      name:'challenge',
      component: challenge
    },


    {
      path: '/create',
      name: 'qmenu',
      component: qmenu
    },
    {
      path:'/qform',
      name: 'qform',
      component: qform
    },

    {
      path: '/shop',
      name: 'shop',
      component: shop
    },

    {
      path: '/owned',
      name: 'owned',
      component: owned
    },

    {
      path:'/ownedList',
      name:'ownedList',
      component:ownedList
    },

    {
      path:'/boughtList',
      name:'boughtList',
      component:boughtList
    },

    {
      path:'/newOrder',
      name: 'newOrder',
      component : newOrder,
      beforeEnter: (to, from, next) => {
        let activeorder=parseInt(window.localStorage.getItem("activeOrder"),10)
        if(activeorder)
        {
          next({name: 'welcome'})
        }
        else
        next()
      }
    },


    {
      path:'/history',
      name: 'history',
      component : history
    },

    {
      path:'/places',
      name: 'places',
      component: places
    },

    {
      path:'/places/:place_id/menu/:name',
      name: 'placeMenu',
      component: placeMenu
    }

   ]
})
