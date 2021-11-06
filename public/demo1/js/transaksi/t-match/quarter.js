class Quarter {

  quarter = null

  constructor() {
    // set last quarter
    const lastQuarter = this.getLastQuarter()
    if (lastQuarter) {
      this.quarter = lastQuarter
    } else {
      this.quarter = null
    }
  }

  setQuarter(value) {
    this.quarter = value
    this.setLastQuarter()
  }

  setLastQuarter() {
    localStorage.setItem('quarter', this.quarter)
  }

  getLastQuarter() {
    return localStorage.getItem('quarter')
  }

  getQuarter() {
    return this.quarter
  }

  clear() {
    this.quarter = null
    localStorage.setItem('quarter', null)
  }
}