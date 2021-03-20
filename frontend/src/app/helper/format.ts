import { GENDER_TYPES } from '@app/variables';

interface UserInfo {
  first_name: string;
  middle_name: string;
  last_name: string;
}

export class Format {
  static date(date) {
    const d = new Date(date);

    return [
      d.getFullYear(),
      Format.addZero(d.getMonth() + 1),
      Format.addZero(d.getDate())
    ].join('-');
  }

  static displayDate(date) {
    const options = { month: 'long', day: 'numeric', year: 'numeric' };
    return new Intl.DateTimeFormat('en-US', options).format(new Date(date));
  }

  static dateTime(date) {
    const options = {
      month: 'short',
      day: 'numeric',
      year: 'numeric',
      hour: 'numeric',
      minute: 'numeric'
    };
    return new Intl.DateTimeFormat('en-US', options).format(new Date(date));
  }

  static addZero(str: any) {
    return `00${str}`.substr(-2);
  }

  static fullName(data: UserInfo) {
    const { first_name, middle_name, last_name } = data;

    const middleName = middle_name ? middle_name[0] + '.' : '';

    return `${last_name}, ${first_name} ${middleName}`;
  }

  static money(value) {
    var formatter = new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'PHP',
      minimumFractionDigits: 2
    });

    return formatter.format(+value);
  }

  static getAge(date) {
    const birthday = new Date(date);
    const ageDifMs = Date.now() - birthday.getTime();
    const ageDate = new Date(ageDifMs);
    return Math.abs(ageDate.getUTCFullYear() - 1970);
  }

  static id(data) {
    if (data.idc_type) {
      return `${data.idc_type.name} #${data.idc_number}`;
    }

    return '';
  }

  static gender(gender) {
    return GENDER_TYPES[gender];
  }

  static discount(discountArray, id) {
    const discountObj = discountArray.find(discount => discount.id == id);

    return discountObj ? `${discountObj.name} (${discountObj.amount}%)` : '';
  }
}
