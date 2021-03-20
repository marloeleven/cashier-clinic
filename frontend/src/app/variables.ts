export const DEFAULT_URL = 'http://localhost:8000/api/';

// USER TYPES
export const SUPER_ADMIN = 'Super Admin';
export const ADMIN = 'Admin';
export const SUPERVISOR = 'Supervisor';
export const CASHIER = 'Cashier';

export const USER_TYPES = {
  SUPER_ADMIN,
  ADMIN,
  SUPERVISOR,
  CASHIER
};

// GENDER TYPES
export const MALE = 'male';
export const FEMALE = 'female';

export const GENDER_TYPES = {
  [MALE]: 'Male',
  [FEMALE]: 'Female'
};

// PRODEDURE TYPES
export const LABORATORY = 'Laboratory';
export const RADIOLOGY = 'Radiology';
export const CONSULTATION = 'Consultation';
export const SEND_IN = 'Send-in';
export const CORPORATE = 'Corporate';

export const PROCEDURE_TYPES = {
  LABORATORY,
  RADIOLOGY,
  CONSULTATION,
  SEND_IN,
  CORPORATE
};

export const toDropdownArray = obj => {
  return Object.keys(obj).reduce((arr, key) => {
    arr.push({ key, value: obj[key] });
    return arr;
  }, []);
};

// LOOKUP TYPES
export const DISCOUNT = 'DISCOUNT';
export const ID_TYPE = 'ID_TYPE';

export const LOOKUP_TYPES = {
  DISCOUNT,
  ID_TYPE
};

export const COLORS_ARRAY = [
  'rgba(255, 99, 132, alpha)',
  'rgba(54, 162, 235, alpha)',
  'rgba(255, 206, 86, alpha)',
  'rgba(75, 192, 192, alpha)',
  'rgba(153, 102, 255, alpha)',
  'rgba(255, 159, 64, alpha)',
  'rgba(70, 97, 238, alpha)',
  'rgba(236, 86, 87, alpha)',
  'rgba(27, 205, 209, alpha)',
  'rgba(143, 170, 187, alpha)',
  'rgba(176, 139, 235, alpha)',
  'rgba(62, 160, 221, alpha)',
  'rgba(245, 165, 42, alpha)',
  'rgba(35, 191, 170, alpha)',
  'rgba(250, 165, 134, alpha)',
  'rgba(235, 140, 198, alpha)',
  'rgba(47, 79, 79, alpha)',
  'rgba(0, 128, 128, alpha)',
  'rgba(46, 139, 87, alpha)',
  'rgba(60, 179, 113, alpha)',
  'rgba(144, 238, 144, alpha)'
];

export const GENERIC = 'generic';

export const REPORT_TYPES = {
  [GENERIC]: 'Generic',
  cbc: 'CBC',
  chemistry: 'Chemistry',
  ecg: 'ECG',
  parasitology: 'Parasitology',
  serology: 'Serology',
  urinalysis: 'Urinalysis'
};
