export type TagSelection = {
  id: string | number;
  value: 'positive' | 'negative';
};

export interface FilterCheckboxGroupContext {
  isPositive: (id: string | number) => boolean;
  isNegative: (id: string | number) => boolean;
  setTag: (id: string | number, v: 'positive' | 'negative' | null) => void;
}

export interface FilterParams {
  tags: string | null;
  range: [] | null;
  no_date: boolean;
}

export const FILTER_CHECKBOX_GROUP_SYMBOL = Symbol('filterCheckboxGroup');
