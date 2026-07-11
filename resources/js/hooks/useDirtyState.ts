import { useCallback, useEffect, useRef } from "react";

export function useDirtyState(isDirty: boolean, onClose: () => void) {
  const isDirtyRef = useRef<boolean>(isDirty);

  useEffect(() => {
    isDirtyRef.current = isDirty;
  }, [isDirty]);

  const handleClose = useCallback(() => {
    if (!isDirtyRef.current) {
      onClose();
      return;
    }

    const confirmed = window.confirm(
      'You have unsaved changes. Are you sure you want to close?'
    );

    if (confirmed) onClose();
  }, [onClose]);

  return handleClose;
}