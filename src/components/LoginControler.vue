<template>
  <div id="loginControl">
    <a 
      class="btn btn-outline-primary" 
      href="#" 
      @click.prevent="openWin(1)" 
      v-if="!this.$store.getters.getCurrentUser.id">Войти</a>
    <router-link 
      class="btn btn-outline-primary" 
      :to="'/account/' + parseInt(this.$store.getters.getCurrentUser.id)"
      v-else>Личный кабинет
    </router-link>

    <div class="cover shadow-sm p-3 mb-5 bg-white rounded" v-if="showWin === 1"> <!-- login -->
      <div class="container">

        <button type="button" class="close pointer zindex-10" aria-label="Close" @click="openWin(0)"> 
          <span aria-hidden="true">&times;</span>
        </button>

        <div class="row">
          <div class="col">

            <form @submit.prevent="submitLogin" novalidate> <!-- class="was-validated" -->
              <div class="form-group">
                <label for="email">Email:</label>
                <input 
                  type="email" 
                  class="form-control" 
                  aria-describedby="emailHelp"
                  id="email"
                  v-model.trim="email"
                  :class="{ 'is-invalid' : emailError }"
                  @input="inputEmail">
                <small 
                  id="emailHelp" 
                  class="form-text text-danger" v-if="errorEmail.minLength">email не должен быть пустым</small>
                <small 
                  id="emailHelp"
                  class="form-text text-danger" v-else-if="errorEmail.noEmail">Введите корректный email</small>
                <small 
                  id="emailHelp"
                  class="form-text text-danger" v-else-if="errorEmail.wrongEmail">{{ errorEmail.errorText }}</small>
              </div>
              <div class="form-group">
                <label for="pass">Пароль:</label>
                <input 
                  type="password" 
                  class="form-control" 
                  id="pass" 
                  v-model.trim="pass"
                  @input="inputPass">
              </div>

              <div class="form-group text-center">
                <a href="#" class="d-inline" @click.prevent="openWin(2)"> Забыли пароль? </a> | 
                <a href="#" class="d-inline" @click.prevent="openWin(3)"> Регистрация </a>
              </div>
              <!-- btn submit -->
              <button class="btn btn-primary" type="button" disabled v-if="showWait">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Загрузка...
              </button>
              <button type="submit" class="btn btn-primary" v-else>
                Войти
              </button>
            </form>

          </div>
        </div>
      </div>
    </div>

    <div class="cover shadow-sm p-3 mb-5 bg-white rounded" v-if="showWin === 2"> <!-- reset pass -->
      <div class="container">

        <button type="button" class="close pointer" aria-label="Close" @click="openWin(0)"> 
          <span aria-hidden="true">&times;</span>
        </button>

        <div class="row">
          <div class="col">

            <form @submit.prevent="submitResetPass" novalidate> <!-- class="was-validated" -->
              <div class="form-group">
                <label for="email">Email:</label>
                <input 
                  type="email" 
                  class="form-control valid" 
                  aria-describedby="emailHelp" 
                  id="email" 
                  v-model.trim="email"
                  :class="{ 'is-invalid' : emailError }"
                  @input="inputEmail">
                <small 
                  id="emailHelp" 
                  class="form-text text-danger" v-if="errorEmail.minLength">email не должен быть пустым</small>
                <small 
                  id="emailHelp"
                  class="form-text text-danger" v-else-if="errorEmail.noEmail">Введите корректный email</small>
                <small 
                  id="emailHelp"
                  class="form-text text-danger" v-else-if="errorEmail.wrongEmail">{{ errorEmail.errorText }}</small>
              </div>
              <div class="form-group text-center">
                <a href="#" class="d-inline" @click.prevent="openWin(1)"> Войти </a> | 
                <a href="#" class="d-inline" @click.prevent="openWin(3)"> Регистрация </a>
              </div>
              <!-- btn submit -->
              <button class="btn btn-primary" type="button" disabled v-if="showWait">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Загрузка...
              </button>
              <button type="submit" class="btn btn-primary" v-else>
                Выслать пароль
              </button>
            </form>

          </div>
        </div>
      </div>
    </div>

    <div class="cover shadow-sm p-3 mb-5 bg-white rounded" v-if="showWin === 3"> <!-- registr -->
      <div class="container">

        <button type="button" class="close pointer" aria-label="Close" @click="openWin(0)"> 
          <span aria-hidden="true">&times;</span>
        </button>

        <div class="row">
          <div class="col">

            <form @submit.prevent="submitReg" novalidate> <!-- class="was-validated" -->
              <div class="form-group">
                <label for="email">Email:</label>
                <input 
                  type="email" 
                  class="form-control" 
                  aria-describedby="emailHelp" 
                  id="email"
                  v-model.trim="email"
                  :class="{ 'is-invalid' : emailError }"
                  @input="inputEmail">
                <small 
                  id="emailHelp" 
                  class="form-text text-danger" v-if="errorEmail.minLength">Email не должен быть пустым</small>
                <small 
                  id="emailHelp"
                  class="form-text text-danger" v-else-if="errorEmail.noEmail">Введите корректный email</small>
                <small 
                  id="emailHelp"
                  class="form-text text-danger" v-else-if="errorEmail.wrongEmail">{{ errorEmail.errorText }}</small>
                <small 
                  id="emailHelp"
                  class="form-text text-info" v-else>{{ email.length }} / {{ maxLengthEmail }}</small>  
              </div>
              <div class="form-group">
                <label for="pass">Пароль:</label>
                <input 
                  type="password" 
                  class="form-control" 
                  id="pass" 
                  v-model.trim="pass"
                  :class="{ 'is-invalid' : passError }"
                  @input="inputPass">
                <small 
                  id="PassHelp" 
                  class="form-text text-danger" v-if="errorPass.minLength">Пароль не должен быть пустым</small>
                <small 
                  id="emailHelp"
                  class="form-text text-info" v-else>{{ pass.length }} / {{ maxLengthPass }}</small> 
              </div>
              <div class="form-group">
                <label for="confirmPass">Подтверждение пароля:</label>
                <input 
                  type="password" 
                  class="form-control" 
                  id="confirmPass" 
                  aria-describedby="confPassHelp" 
                  v-model.trim="confirmPass"
                  :class="{
                    'is-invalid': errorPass.confPass === true, 
                    'is-valid': errorPass.confPass === false && this.confirmPass.length !== 0 
                  }"
                  @input="inputConfPass">
                <small 
                  id="confPassHelp" 
                  class="form-text text-danger" v-if="errorPass.confPass">Пароль и подтверждение не совпадают</small>
              </div>
              <div class="form-group">
                <button class="btn btn-info" type="button" disabled v-if="showTimeOut">
                  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    повторить через {{ timeOutSendCode }} сек.
                </button>
                <template v-else>
                  <button class="btn btn-info" type="button" disabled v-if="showWaitSendCode">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Загрузка...
                  </button>
                  <button type="button" class="btn btn-info" @click="sendCode" v-else>Выслать код</button>
                </template>
              </div>
              <div class="form-group">
                <label for="code">Проверочный код:</label>
                <input 
                  type="text" 
                  class="form-control" 
                  id="code" 
                  aria-describedby="codeHelp" 
                  v-model.trim="inputCode" 
                  :class="{ 'is-invalid' : codeError }">
                <small 
                  id="codeHelp" 
                  class="form-text text-danger" 
                  v-if="errorCode.wrongCode">Проверочный код не совпадает</small>
                <small 
                  id="codeHelp" 
                  class="form-text text-danger" 
                  v-else-if="errorCode.wrongEmail">Email не совпадает с проверочным</small>
                
              </div>
              <div class="form-group text-center">
                <a href="#" class="d-inline" @click.prevent="openWin(1)"> Войти </a> | 
                <a href="#" class="d-inline" @click.prevent="openWin(2)"> Забыли пароль? </a>
              </div>
              <button type="submit" class="btn btn-primary">Регистрация</button>
            </form>

          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
  // component: LoginControler
  // version: 0.0.2
  // date: 04.2020
  // sagit117@gmail.com 
export default {
  props: {
    stateWin: Number,     // принимает свойства определяющее какое окно нужно отобразить
  },

  data() {
    return {
      showWin: 0,               // свойства определяющее какое окно нужно отобразить
      errorEmail: {
        minLength: false, 
        noEmail: false,
        wrongEmail: false,
        errorText: ""
      },
      errorPass: {
        minLength: false,
        confPass: null
      },
      errorCode: {
        wrongCode: false,       // не верный код
        wrongEmail: false       // не совпадает email
      },
      pass: "",
      email: "",
      confirmPass: "",
      showWait: false,          // ожидание загрузки
      showWaitSendCode: false, 
      timeOutSendCode: 30,      // время залержки между высылкой кода
      showTimeOut: false,
      code: "",                 // проверочный код из бэкенда
      emailCode: "",            // email на который был выслан код
      inputCode: "",            // проверочный код введенный пользователем
      maxLengthEmail: 64,
      maxLengthPass: 32
    }
  },

  watch: {
    showWin: function() {
      this.errorEmail = {
        minLength: false,
        noEmail: false,
        wrongEmail: false,
        errorText: ""
      };
      this.errorPass = {
        minLength: false,
        confPass: null
      };
      this.errorCode = {
        wrongCode: false,
        wrongEmail: false
      };
      this.pass = "";
      this.email = "";
      this.confirmPass = "";
      this.code = "";
      this.emailCode = "";
      this.inputCode = "";
    }
  },

  created() {
    if (this.stateWin) this.showWin = this.stateWin;
  },

  computed: {
    emailError: function() {
      for (let key in this.errorEmail) {
        if (this.errorEmail[key]) return true;
      }
      return false;
    },
    passError: function() {
      for (let key in this.errorPass) {
        if (this.errorPass[key] && key !== 'confPass') return true;
      }
      return false;
    },
    codeError: function() {
      for (let key in this.errorCode) {
        if (this.errorCode[key]) return true;
      }
      return false;
    }
  },

  methods: {
    openWin(state) {
      this.showWin = state;
    },
    minLength(value) {
      if (value.length === 0) return true;
      return false; 
    },
    inputEmail() {
      if (this.email.length > this.maxLengthEmail) this.email = this.email.slice(0, this.maxLengthEmail);
      this.errorEmail.minLength = this.minLength(this.email);
      if (this.errorEmail.noEmail) this.errorEmail.noEmail = !this.testEmail(this.email);
      if (this.errorEmail.wrongEmail) {
        this.errorEmail.wrongEmail = false;
        this.errorEmail.errorText = "";
      }
    },
    inputPass() {
      if (this.pass.length > this.maxLengthPass) this.pass = this.pass.slice(0, this.maxLengthPass);
      this.errorPass.minLength = this.minLength(this.pass);
      if (this.confirmPass.length > 0) this.inputConfPass();
      if (this.errorEmail.wrongEmail) {
        this.errorEmail.wrongEmail = false;
        this.errorEmail.errorText = "";
      }
    },
    inputConfPass() {
      this.errorPass.confPass = this.pass !== this.confirmPass;
      //if (this.confirmPass.length === 0) this.errorPass.confPass = null;
    },
    testEmail(email) {
      let reg = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/;
			return reg.test(email);
		},
    submitLogin() {
      this.errorEmail.minLenth = this.minLength(this.email);
      this.errorEmail.noEmail = !this.testEmail(this.email);

      if (!this.emailError) {
        // Залогинеть
        this.showWait = true;

        const formData = new FormData();
        formData.append('login', this.email);
        formData.append('pass', this.pass);

        this.$store.dispatch('userLoginedWithPass', formData)
        .then((res) => {
          // удачно
          this.openWin(0);
          this.showWait = false;
          localStorage.setItem("userHash", res);
          localStorage.setItem("userLogin", this.email);
        })
        .catch((errorText) => {
          this.errorEmail.wrongEmail = true;
          this.errorEmail.errorText = errorText;
          this.showWait = false;
        });
      }
    },
    submitResetPass() {
      this.errorEmail.minLength = this.minLength(this.email);
      this.errorEmail.noEmail = !this.testEmail(this.email);

      if (!this.emailError) {
        // Выслать пароль
        this.showWait = true;

        this.$store.dispatch('sendNewPass', this.email)
        .then(() => {
          this.showWait = false;
          this.openWin(1);
        })
        .catch((errorText) => {
          this.errorEmail.wrongEmail = true;
          this.errorEmail.errorText = errorText;
          this.showWait = false;
        });
      }
    },
    submitReg() {
      this.errorEmail.minLength = this.minLength(this.email);
      this.errorEmail.noEmail = !this.testEmail(this.email);
      this.errorPass.minLength = this.minLength(this.pass);
      this.errorPass.confPass = this.pass !== this.confirmPass;
      this.errorCode.wrongEmail = this.email !== this.emailCode;
      this.errorCode.wrongCode = this.code !== this.inputCode || this.code.length === 0;

      if (!this.emailError && !this.passError && !this.codeError) {
        // зарегистрировать
        this.showWait = true;

        const formData = new FormData();
        formData.append('email', this.email);
        formData.append('pass', this.pass);

        this.$store.dispatch("regUser", formData)
        .then(() => {
          this.showWait = false;
          this.openWin(1);
        })
        .catch((errorText) => {
          this.errorEmail.wrongEmail = true;
          this.errorEmail.errorText = errorText;
          this.showWait = false;
        })
      }
    },
    sendCode() {
      this.errorEmail.minLength = this.minLength(this.email);
      this.errorEmail.noEmail = !this.testEmail(this.email);

      if (!this.emailError) {
        // Выслать код
        this.showWaitSendCode = true;
        this.$store.dispatch('sendCode', this.email)
        .then((response) => {
          this.showWaitSendCode = false;

          const interval = setInterval(()=> {
            this.showTimeOut = true;
            this.timeOutSendCode--;
            this.code = response.code;
            this.emailCode = response.post;

            if (this.timeOutSendCode === 0) {
              clearInterval(interval);
              this.timeOutSendCode = 30;
              this.showTimeOut = false;
            }
          }, 1000);

        })
        .catch((errorText) => {
          this.errorEmail.wrongEmail = true;
          this.errorEmail.errorText = errorText;
          this.showWaitSendCode = false;
        })
      }
    },

  },
    
}
</script>

<style lang="scss" scoped>
  .cover {
    position: absolute;
    left: 50%;
    top: 50%;
    width: 300px;
    transform: translate(-50%,-50%);
    z-index: 10;
  }
  .zindex-10 {
    z-index: 10;
  }
</style>