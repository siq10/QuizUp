import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)


export const store = new Vuex.Store({
  state: {
    token: null,
    user:{},
    menu_items:[],
    category_id:0,
    gameon:0,
    matchData:{},

    OneSignal:2,
    device:"",
    notificationTabTrigger:false,
    notificationsCount:0,
    notificationsButton:0,
    notifications:{
      names:[],
      times:[],
      ids:[],
      guids:[]
    },
    isAuthenticated:0
  },

  mutations: {
    STORE_AUTHBOOL (state,auth)
    {
      state.isAuthenticated = auth
    },
    STORE_TOKEN (state, token) {
      state.token = token
      window.localStorage.setItem('TOKEN', token)
    },
    STORE_USER(state, user) {
    	state.user = user
    	window.localStorage.setItem('USER',JSON.stringify(user))
    },
    STORE_MENU_ITEMS(state,menu_items) {
    	state.menu_items = menu_items
    },
    STORE_CATEGORY_ID(state,category_id) {
      state.category_id = category_id
    },
    STORE_OS(state,onesignal)
    {
      state.OneSignal = onesignal
    },
    STORE_DEVICE(state,device)
    {
      state.device = device
    },
    STORE_NOTIFICATION_TRIGGER(state)
    {
      state.notificationTabTrigger = !state.notificationTabTrigger
    },
    STORE_NOTIFICATION_BUTTON(state)
    {
      state.notificationsButton = 1 - state.notificationsButton
    },
    STORE_NOTIFICATIONS(state,obj)
    {
      state.notifications = obj
    },
    UPDATE_NOTIFICATION_COUNT(state,unit){
      state.notificationsCount+=unit
    },
    UPDATE_NOTIFICATIONS(state,index)
    {
      state.notifications.names.splice(index, 1);
      state.notifications.ids.splice(index, 1);
      state.notifications.times.splice(index, 1);
      state.notifications.guids.splice(index,1);

    },
    UPDATE_GAME_DATA(state,data)
    {
      state.matchData = data
    },
    ADD_NOTIFICATION(state,notification)
    {
      state.notifications.names.unshift(notification.name)
      state.notifications.ids.unshift(notification.sender)
      state.notifications.times.unshift(notification.time)
      state.notifications.guids.unshift(notification.guid)
    },
    STORE_NOTIFICATION_COUNT(state,count)
    {
      state.notificationsCount = count
    },
    SHOW_ACTUAL_GAME_COMPONENT(state,show)
    {
      state.gameon = show
    }
  },

  actions: {
    storeAuth({commit},auth)
    {
      commit("STORE_AUTHBOOL",auth)
    },
  	storeToken({commit}, token) {
  		commit('STORE_TOKEN', token)
  	},

	 storeUser({commit}, user) {
  		commit('STORE_USER', user)
  	},
  	storeMenu({commit}, menu_items)
  	{
  		commit('STORE_MENU_ITEMS',menu_items)
  	},
    storeCategory({commit}, category_id)
    {
      commit('STORE_CATEGORY_ID',category_id)
    },
    storeOneSignal({commit},onesignal)
    {
      commit("STORE_OS",onesignal)
    },
    storeDevice({commit},device)
    {
      commit("STORE_DEVICE",device)
    },
    triggerNotifications({commit},notificationTabTrigger)
    {
      commit("STORE_NOTIFICATION_BUTTON") //-----------------
      commit("STORE_NOTIFICATION_TRIGGER",notificationTabTrigger)
    },
    storeNotifications({commit},notifications)
    {
      commit("STORE_NOTIFICATIONS",notifications)
    },
    updateNotifications({commit},index)
    {
      commit("UPDATE_NOTIFICATIONS", index)
      commit("UPDATE_NOTIFICATION_COUNT",-1)
    },
    addNotification({commit},notification)
    {
      commit("ADD_NOTIFICATION",notification)
      commit("UPDATE_NOTIFICATION_COUNT",1)

    },
    storeNotificationCount({commit},count)
    {
      commit("STORE_NOTIFICATION_COUNT",count)
    },
    showGameOn({commit},show){
      commit("SHOW_ACTUAL_GAME_COMPONENT",show)
    },
    updateMatchData({commit},data)
    {
      commit("UPDATE_GAME_DATA",data)
    }
  }
})
