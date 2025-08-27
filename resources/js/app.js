import * as bootstrap from 'bootstrap'
import CreateDataTable from './DataTableFactory.js';
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
import AirDatepicker from 'air-datepicker'
import LocaleDE from 'air-datepicker/locale/de'
import 'air-datepicker/air-datepicker.css'

export * from './LivewireExtensions'

window.CreateDataTable = CreateDataTable
window.bootstrap = bootstrap
window.Swiper = Swiper
AirDatepicker.DefaultLocale = LocaleDE
window.AirDatepicker = AirDatepicker
