import { computed, inject } from 'vue';
import type { FilterCheckboxGroupContext } from '@/entities/image/model';
import { FILTER_CHECKBOX_GROUP_SYMBOL } from '@/entities/image/model';

export function useFilterCheckbox(tagId: string | number) {
  // Инъекция контекста группы
  const group = inject<FilterCheckboxGroupContext>(FILTER_CHECKBOX_GROUP_SYMBOL);

  if (!group) {
    throw new Error('FilterImagesCheckbox должен использоваться внутри FilterImagesCheckboxGroup');
  }

  // Вычисляемые свойства для положительного чекбокса
  const positiveChecked = computed({
    get: () => group.isPositive(tagId),
    set: (checked: boolean) => {
      group.setTag(tagId, checked ? 'positive' : null);
    },
  });

  // Вычисляемые свойства для отрицательного чекбокса
  const negativeChecked = computed({
    get: () => group.isNegative(tagId),
    set: (checked: boolean) => {
      group.setTag(tagId, checked ? 'negative' : null);
    },
  });

  return {
    positiveChecked,
    negativeChecked,
    group,
  };
}
