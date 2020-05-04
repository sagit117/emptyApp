<template>
  <div class="container">
    <div class="row">
      <div class="col">
        <h4 class="mb-3">Личный кабинет пользователя {{ user.post }}</h4>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4 col-md-6">

        <form novalidate @submit.prevent="submitData">
          <div class="row">
            <div class="col-6 d-flex justify-content-start">
              <h5>Личные данные</h5>
            </div>
            <div class="col-6 d-flex justify-content-end">
              <button type="button" class="btn btn-info" @click="exitAccount">Выйти из аккаунта</button>
            </div>
          </div>
          <div class="form-group">
            <label for="name">Имя:</label>
            <input 
              type="text" 
              class="form-control" 
              aria-describedby="nameHelp"
              id="name"
              v-model.trim="name"
              :class="{ 'is-invalid' : nameError }"
              @input="inputName">
            <small 
              id="nameHelp" 
              class="form-text text-danger" v-if="errorName.minLength">Имя не должено быть пустым</small>
            <small 
              id="nameHelp" 
              class="form-text text-danger" v-else-if="errorName.lang">Имя должно содержать только буквы алфавита</small>
            <small 
              id="nameHelp"
              class="form-text text-info" v-else>{{ name.length }} / {{ maxLength }}</small> 
          </div>
          <div class="form-group">
            <label for="surname">Фамилия:</label>
            <input 
              type="text" 
              class="form-control" 
              aria-describedby="surnameHelp"
              id="surname"
              v-model.trim="surname"
              :class="{ 'is-invalid' : surnameError }"
              @input="inputSurname">
            <small 
              id="surnameHelp" 
              class="form-text text-danger" v-if="errorSurname.lang">Фамилия должна содержать только буквы алфавита</small>
            <small 
              id="surnameHelp"
              class="form-text text-info" v-else>{{ surname.length }} / {{ maxLength }}</small>
          </div>
          <div class="form-group">
            <label for="patronymic">Отчество:</label>
            <input 
              type="text" 
              class="form-control" 
              aria-describedby="patronymicHelp"
              id="patronymic"
              v-model.trim="patronymic"
              :class="{ 'is-invalid' : patronError }"
              @input="inputPatron">
            <small 
              id="patronymicHelp" 
              class="form-text text-danger" v-if="errorPatron.lang">Отчество должно содержать только буквы алфавита</small>
            <small 
              id="patronymicHelp"
              class="form-text text-info" v-else>{{ patronymic.length }} / {{ maxLength }}</small>
            <!-- btn submit -->
            <button class="btn btn-primary mt-3" type="button" disabled v-if="showWait">
              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
              Загрузка...
            </button>
            <button type="submit" class="btn btn-primary mt-3" v-else>
              Изменить
            </button>
          </div>

        </form>

        <form class="mt-5" novalidate @submit.prevent="submitPass">
          <h5>Смена пороля</h5>
          <div class="form-group">
            <label for="oldPass">Старый пароль:</label>
            <input 
              type="password" 
              class="form-control"
              id="oldPass"
              v-model.trim="oldPass">  
          </div>
          <div class="form-group">
            <label for="newPass">Новый пароль:</label>
            <input 
              type="password" 
              class="form-control" 
              aria-describedby="newPassHelp"
              id="newPass"
              v-model.trim="newPass"
              @input="inputNewPass"
              :class="{ 'is-invalid': passError}">
            <small 
              id="newPassHelp" 
              class="form-text text-danger" v-if="errorPass.minLength">Пароль не должен быть пустым</small>
            <small 
              id="newPassHelp" 
              class="form-text text-danger" v-else-if="errorPass.conf">Пароль и подтверждение не совпадают</small>
            <small 
              id="newPassHelp" 
              class="form-text text-info" v-else>{{ newPass.length }} / {{ maxLength }}</small> 
          </div>
          <div class="form-group">
            <label for="confirmPass">Подтверждение пароля:</label>
            <input 
              type="password" 
              class="form-control" 
              id="confirmPass"
              v-model.trim="confirmPass"
              @input="inputConfPass">
            <!-- btn submit -->
            <button class="btn btn-primary mt-3" type="button" disabled v-if="showWaitPass">
              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
              Загрузка...
            </button>
            <button type="submit" class="btn btn-primary mt-3" v-else>
              Изменить
            </button>
          </div>

        </form>

      </div>
    </div>
  </div>
</template>

<script>
  // component Account
  // version: 0.0.2
  // date: 05.2020
  // sagit117@gmail.com 
export default {
  data() {
    return {
      name: '',
      surname: '',
      patronymic: '',
      oldPass: '',
      newPass: '',
      confirmPass: '',
      showWait: false,
      showWaitPass: false,
      user: {},
      errorName: {
        minLength: false,
        lang: false
      },
      errorSurname: {
        lang: false
      },
      errorPatron: {
        lang: false
      },
      errorPass: {
        minLength: false,
        conf: false,
      },
      maxLength: 32
    }
  },

  created() {
    if (this.$route.params.id !== this.$store.getters.getCurrentUser.id && this.$store.getters.getCurrentUser.rule !== 'admin') {
      this.$router.push("/");
    } else {
      // dispatch
      const data = {
        id: this.$route.params.id,
        hash: localStorage.getItem('userHash'),
        login: this.$store.getters.getCurrentUser.post
      }
      this.$store.dispatch('getUserWithID', data)
      .then((response) => {
        this.user = response;
        this.name = (!this.user.name) ? '' : this.user.name;
        this.surname = (!this.user.surname) ? '' : this.user.surname;
        this.patronymic = (!this.user.patronymic) ? '' : this.user.patronymic;
      })
      .catch(() => {});
    }
  },

  computed: {
    nameError: function() {
      for (let key in this.errorName) {
        if (this.errorName[key]) return true;
      }
      return false;
    },
    surnameError: function() {
      for (let key in this.errorSurname) {
        if (this.errorSurname[key]) return true;
      }
      return false;
    },
    patronError: function() {
      for (let key in this.errorPatron) {
        if (this.errorPatron[key]) return true;
      }
      return false;
    },
    passError: function() {
      for (let key in this.errorPass) {
        if (this.errorPass[key]) return true;
      }
      return false;
    }
  },

  methods: {
    testInput(value) {
			if (value === '') return true;
			let reg = /^[a-zа-яё\s]+$/iu; //[А-Я-Ё]/gi;
			return reg.test(value);
    },
    inputName() {
      this.errorName.minLength = this.name.length === 0;
      if (this.name.length > this.maxLength) this.name = this.name.slice(0, this.maxLength);
      this.errorName.lang = !this.testInput(this.name);
    },
    inputSurname() {
      if (this.surname.length > this.maxLength) this.surname = this.surname.slice(0, this.maxLength);
      this.errorSurname.lang = !this.testInput(this.surname);
    },
    inputPatron() {
      if (this.patronymic.length > this.maxLength) this.patronymic = this.patronymic.slice(0, this.maxLength);
      this.errorPatron.lang = !this.testInput(this.patronymic);
    },
    inputConfPass() {
      this.errorPass.conf = this.confirmPass !== this.newPass;
    },
    inputNewPass() {
      if (this.newPass.length > this.maxLength) this.newPass = this.newPass.slice(0, this.maxLength);
      this.errorPass.minLength = this.newPass.length === 0;
    },
    submitData() {
      this.errorName.minLength = this.name.length === 0;

      if (!this.nameError && !this.surnameError && !this.patronError) {
        // Изменить данные
        this.showWait = true;
        const formData = new FormData();
        formData.append('name', this.name);
        formData.append('surname', this.surname);
        formData.append('patronymic', this.patronymic);
        formData.append('id', this.user.id);
        formData.append('hash', localStorage.getItem('userHash'));
        formData.append('login', this.$store.getters.getCurrentUser.post);

        this.$store.dispatch('saveUserData', formData)
        .then(() => {
          this.showWait = false;
          if (this.user.id === this.$store.getters.getCurrentUser.id) {
            this.user.name = this.name;
            this.user.surname = this.surname;
            this.user.patronymic = this.patronymic;

            this.$store.commit('setCurrentUser', this.user);
          }
        })
        .catch(() => {
          this.showWait = false;
        })
      }
    },
    submitPass() {
      this.errorPass.minLength = this.newPass.length === 0;
      this.errorPass.conf = this.confirmPass !== this.newPass;

      if (!this.errorPass.minLength && !this.errorPass.conf) {
        // отослать данные 
        this.showWaitPass = true;
        const formData = new FormData();
        formData.append('oldPass', this.oldPass);
        formData.append('newPass', this.newPass);
        formData.append('id', this.user.id);
        formData.append('hash', localStorage.getItem('userHash'));
        formData.append('login', this.$store.getters.getCurrentUser.post);

        this.$store.dispatch('changePass', formData)
        .then(() => {
          this.showWaitPass = false;
          this.oldPass = '';
          this.newPass = '';
          this.confirmPass = '';
        })
        .catch(() => {
          this.showWaitPass = false;
        })
      }
    },
    exitAccount() {
      if (confirm("Уверены, что хотите выйти из аккаунта? ")) {
        localStorage.removeItem('userHash');
        localStorage.removeItem('userLogin');
        this.$store.commit('setCurrentUser', {});
        this.$router.push('/');
      }
    }

  }

}
</script>