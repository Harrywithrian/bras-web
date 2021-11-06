
// dayjs duration
dayjs.extend(window.dayjs_plugin_duration)

class Timer {

  selector = {
    timerDisplay: null,
    timerControl: null,
    timerStart: null,
    timerStop: null,
    timerPause: null,
  }
  defaultDuration = null
  duration = null
  time = null
  timeFormat = 'mm:ss'
  timer = null

  constructor(duration, selector) {
    this.defaultDuration = duration
    this.selector = selector
    // init
    // get last duration from storage
    const lastDuration = this.getLastDuration()
    if (lastDuration) {
      this.duration = lastDuration
      console.log('last duration', this.duration)
    } else {
      this.duration = duration
      console.log('new init', this.duration)
    }

    // init time
    this.time = dayjs.duration(this.duration)
    // init control
    this.showStartControl()
  }

  displayTime() {
    const timeDisplay = this.time.format(this.timeFormat)
    this.selector.timerDisplay.text(timeDisplay)
  }

  start() {
    this.showPauseControl()
    var that = this
    this.timer = setInterval(function () {
      that.time = that.time.subtract(1, 'seconds')
      that.displayTime()
      that.duration = that.time.$d
      that.setLastDuration(that.duration)
    }, 1000)
  }

  stop() {
    clearInterval(this.timer)
    // reset timer
    this.duration = this.defaultDuration
    this.time = dayjs.duration(this.duration)
    this.setLastDuration()
    this.displayTime()
    this.showStartControl()
  }

  pause(callback) {
    this.showStartControl()
    clearInterval(this.timer)
    callback({ duration: this.duration, time: this.time, formattedTime: this.time.format('mm:ss') })
  }

  setLastDuration() {
    localStorage.setItem('time', JSON.stringify(this.time))
  }

  getLastDuration() {
    const time = localStorage.getItem('time')
    if (!time) return false
    return JSON.parse(time)
  }

  isInit() {
    return !!this.time
  }

  showStartControl() {
    this.selector.timerStart.show()
    this.selector.timerPause.hide()
  }

  showPauseControl() {
    this.selector.timerStart.hide()
    this.selector.timerPause.show()
  }

  clear() {
    clearInterval(this.timer)
    this.selector = {
      timerDisplay: null,
      timerControl: null,
      timerStart: null,
      timerStop: null,
      timerPause: null,
    }
    this.duration = null
    this.time = null
    this.timeFormat = 'mm:ss'
    this.timer = null
    localStorage.setItem('quarter', null)
  }
}