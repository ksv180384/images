import { computed } from 'vue';

import type { ComputedRef, WritableComputedRef } from 'vue';
import type { TagSelection, FilterCheckboxGroupContext } from '@/entities/image/model';

export function useFilterCheckboxGroup(
  modelValue: ComputedRef<TagSelection[]>,
  emit: (event: 'update:modelValue', value: TagSelection[]) => void
) {
  // Двусторонний вычисляемый реф для удобной работы
  const value: WritableComputedRef<TagSelection[]> = computed({
    get: () => modelValue.value ?? [],
    set: (v) => emit('update:modelValue', v),
  });

  // Установка/удаление тега
  function setTag(id: string | number, v: 'positive' | 'negative' | null) {
    const arr = [...value.value];
    const idx = arr.findIndex((t) => t.id === id);

    if (v === null) {
      // Удалить тег
      if (idx !== -1) arr.splice(idx, 1);
    } else {
      // Добавить или обновить тег
      if (idx === -1) {
        arr.push({ id, value: v });
      } else {
        arr[idx] = { id, value: v };
      }
    }

    value.value = arr;
  }

  // Проверка статуса тега
  function isPositive(id: string | number): boolean {
    return value.value.some((t) => t.id === id && t.value === 'positive');
  }

  function isNegative(id: string | number): boolean {
    return value.value.some((t) => t.id === id && t.value === 'negative');
  }

  // Контекст для provide
  const groupContext: FilterCheckboxGroupContext = {
    isPositive,
    isNegative,
    setTag,
  };

  return {
    value,
    setTag,
    isPositive,
    isNegative,
    groupContext,
  };
}
