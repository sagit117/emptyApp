import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    // app
    domainName: "https://www.axel-dev.ru.com/shop3/", // имя домена для обращение к api
    
    // user
    currentUser: {},                                  // текущий пользователь

    // Alert
    alert: {
      show: false,
      title: "",
      text: "",
      state: 0
    },

    // Wait
    showWait: false,                                  // ожидание
  },
  mutations: {
    setCurrentUser(state, data) {
      state.currentUser = data;
    },
    setShowAlert(state, data) {
      state.alert = data;
    },
    setShowWait(state, data) {
      state.showWait = data;
    },
  },
  actions: {
    userLoginedWithPass(context, data) {  // логинем пользователя по логину и паролю
      return new Promise((resolve, reject) => {
        axios.post(context.state.domainName + 'api/c_login.php', data)
        .then((response) => {
          if (response.data.errorCode !== '') {
            reject(response.data.errorText);
          } else {
            // успех
            context.commit('setCurrentUser', response.data.users);
            resolve(response.data.hash);
          }
        })
        .catch((error) => {
          console.log(error);
          reject(); 
          // обработчик сообщений
          context.commit('setShowAlert', 
          { show: true, 
            title: "Ошибка", 
            text: "Проблема на линии, попробуйте позже!",
            state: 2
          });
        })
      })
    },
    sendNewPass(context, data) {          // выслать востановленный пароль
      return new Promise((resolve, reject) => {
        axios.get(context.state.domainName + 'api/c_sendNewPass.php?login=' + data)
        .then((response) => {
          if (response.data.errorCode !== '') {
            reject(response.data.errorText);
          } else {
            resolve();
            context.commit('setShowAlert', 
            { show: true, 
              title: "Успешно", 
              text: "Новый пароль отправлен на указанный email, так же проверьте папку СПАМ!",
              state: 1
            });
          }
        })
        .catch((error) => {
          console.log(error);
          reject(); 
          // обработчик сообщений
          this.commit('setShowAlert', 
          { show: true, 
            title: "Ошибка", 
            text: "Проблема на линии, попробуйте позже!",
            state: 2
          });
        })
      })
    },
    sendCode(context, data) {             // выслать код для проверки регистрации
      return new Promise((resolve, reject) => {
        axios.get(context.state.domainName + 'api/c_sendCode.php?email=' + data)
        .then((response) => {
          if (response.data.errorCode !== '') {
            reject(response.data.errorText);
          } else {
            context.commit('setShowAlert', 
            { show: true, 
              title: "Успешно", 
              text: "Проверочный код отправлен на указанный email, так же проверьте папку СПАМ!",
              state: 1
            });
            resolve(response.data);
          }
        })
        .catch((error) => {
          console.log(error);
          reject(); 
          // обработчик сообщений
          context.commit('setShowAlert', 
          { show: true, 
            title: "Ошибка", 
            text: "Проблема на линии, попробуйте позже!",
            state: 2
          });
        });
      })
    },
    regUser(context, data) {              // регистрация пользователя
      return new Promise((resolve, reject) => {
        axios.post(context.state.domainName + "api/c_reg.php", data)
        .then((response) => {
          if (response.data.errorCode !== '') {
            reject(response.data.errorText);
          } else {
            context.commit('setShowAlert', 
            { show: true, 
              title: "Успешно", 
              text: "Вы зарегистрированы!",
              state: 1
            });
            resolve(response.data);
          }
        })
        .catch((error) => {
          console.log(error);
          reject(); 
          // обработчик сообщений
          context.commit('setShowAlert', 
          { show: true, 
            title: "Ошибка", 
            text: "Проблема на линии, попробуйте позже!",
            state: 2
          });
        })
      })
    },
    userLoginedWithHash(context, data) {  // логинем пользователя по почте и хешу
      context.commit('setShowWait', true);
      axios.get(context.state.domainName + 'api/c_login.php?login=' + data.userLogin + "&hash=" + data.userHash)
      .then((response) => {
        context.commit('setShowWait', false);
        if (response.data.errorCode === '' && parseInt(response.data.users.id) > 0) {
          context.commit('setCurrentUser', response.data.users);
        }
      })
      .catch((error) => {
        context.commit('setShowWait', false);
        console.log(error);
        // обработчик сообщений
        context.commit('setShowAlert', 
        { show: true, 
          title: "Ошибка", 
          text: "Проблема на линии, попробуйте позже!",
          state: 2
        });
      });
    },
    getUserWithID(context, data) {        // получить пользователя по ИД
      return new Promise((resolve, reject) => {
        context.commit('setShowWait', true);
        axios.get(context.state.domainName + "api/c_getUser.php?id=" + data.id + "&hash=" + data.hash + "&login=" + data.login)
        .then((response) => {
          context.commit('setShowWait', false);
          if (response.data.errorCode === '') {
            resolve(response.data.users);
          } else {
            context.commit('setShowAlert', 
            { show: true, 
              title: "Ошибка", 
              text: response.data.errorText,
              state: 2
            });
            reject();
          }
        })
        .catch((error) => {
          context.commit('setShowWait', false);
          console.log(error);
          // обработчик сообщений
          context.commit('setShowAlert', 
          { show: true, 
            title: "Ошибка", 
            text: "Проблема на линии, попробуйте позже!",
            state: 2
          });
          reject();
        });
      });
    },
    saveUserData(context, data) {         // сохранить данные пользователя
      return new Promise((resolve, reject) => {
        axios.post(context.state.domainName + 'api/c_saveDataUser.php', data)
        .then((response) => {
          if (response.data.errorCode === '') {
            resolve();
            context.commit('setShowAlert', 
            { show: true, 
              title: "Успешно", 
              text: "Данные изменены!",
              state: 1
            });
          } else {
            // обработчик сообщений
            context.commit('setShowAlert', 
            { show: true, 
              title: "Ошибка", 
              text: response.data.errorText,
              state: 2
            });
            reject();
          }
        })
        .catch((error) => {
          console.log(error);
          // обработчик сообщений
          context.commit('setShowAlert', 
          { show: true, 
            title: "Ошибка", 
            text: "Проблема на линии, попробуйте позже!",
            state: 2
          });
          reject();
        });
      });
    },
    changePass(context, data) {           // изменить пароль пользователя
      return new Promise((resolve, reject) => {
        axios.post(context.state.domainName + 'api/c_sendNewPass.php', data)
        .then((response) => {
          if (response.data.errorCode === '') {
            context.commit('setShowAlert', 
            { show: true, 
              title: "Успешно", 
              text: "Данные изменены!",
              state: 1
            });
            resolve();
          } else {
            context.commit('setShowAlert', 
            { show: true, 
              title: "Ошибка", 
              text: response.data.errorText,
              state: 2
            });
            reject();
          }
        })
        .catch((error) => {
          console.log(error);
          // обработчик сообщений
          context.commit('setShowAlert', 
          { show: true, 
            title: "Ошибка", 
            text: "Проблема на линии, попробуйте позже!",
            state: 2
          });
          reject();
        })
      });
    }
  },
  getters: {
    getCurrentUser: state => {
      return state.currentUser;
    },
    getAlert: state => {
      return state.alert;
    },
    getShowWait: state => {
      return state.showWait;
    }

  }
})
