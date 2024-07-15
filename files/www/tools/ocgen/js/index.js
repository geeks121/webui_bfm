const app = new Vue({
  el: '#app',
  data() {
    return {
      wan_ip: "Offline",
      wan_net: "Offline",
      wan_country: "Offline"
    }
  },
  methods: {
    getWanIp() {
      return new Promise((resolve, reject) => {
        axios.get('http://ip-api.com/json?fields=query,country').then((res) => {
          this.wan_ip = res.data.query
          this.wan_country = "(" + res.data.country + ")"
          resolve(res)
        }).catch((error) => {
          this.wan_ip = "Offline"
          this.wan_country = "Offline"
          reject(error)
        })
      })
    },
    intervalGetWanIp() {
      setInterval(() => {
        this.getWanIp()
      }, 1000)
    },
    getWanIsp() {
      return new Promise((resolve, reject) => {
        axios.get('http://ip-api.com/json/?fields=org').then((res) => {
          this.wan_net = res.data.org
          resolve(res)
        }).catch((error) => {
          this.wan_net = "Offline"
          reject(error)
        })
      })
    },
    intervalGetWanIsp() {
      setInterval(() => {
        this.getWanIsp()
      }, 1000)
    }
  },
  created() {
    this.getWanIp().then(() => this.intervalGetWanIp()).catch(() => this.intervalGetWanIp())
    this.getWanIsp().then(() => this.intervalGetWanIsp()).catch(() => this.intervalGetWanIsp())
  }
})
