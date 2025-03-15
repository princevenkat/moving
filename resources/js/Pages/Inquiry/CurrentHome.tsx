import { useForm } from "@inertiajs/react";
import { useState } from "react";

interface Inquiry {
  id: number;
  current_home_type?: string;
  floor?: string;
  rooms?: number;
  square_meters?: number;
  accessibility_long_distance?: boolean;
  accessibility_distance_meters?: number;
  accessibility_has_steps?: boolean;
  accessibility_steps?: number;
  accessibility_impeded?: boolean;
  accessibility_notes?: string;
}

interface CurrentHomeProps {
  inquiry: Inquiry;
}

export default function CurrentHome({ inquiry }: CurrentHomeProps) {
  const { data, setData, post, processing, errors } = useForm({
    current_home_type: inquiry.current_home_type || "",
    floor: inquiry.floor || "",
    rooms: inquiry.rooms || "",
    square_meters: inquiry.square_meters || "",
    accessibility_long_distance: inquiry.accessibility_long_distance || false,
    accessibility_distance_meters: inquiry.accessibility_distance_meters || "",
    accessibility_has_steps: inquiry.accessibility_has_steps || false,
    accessibility_steps: inquiry.accessibility_steps || "",
    accessibility_impeded: inquiry.accessibility_impeded || false,
    accessibility_notes: inquiry.accessibility_notes || "",
  });

  function submit(e: React.FormEvent) {
    e.preventDefault();
    post(route("inquiry.update_current_home", { id: inquiry.id }));
  }

  return (
    <form onSubmit={submit}>
      <h2>Current Home Details</h2>

      {/* Home Type Selection */}
      <label>Select your home type</label>
      <div>
        {["house", "apartment", "shared_flat", "storage", "office"].map((type) => (
          <button
            key={type}
            type="button"
            className={data.current_home_type === type ? "selected" : ""}
            onClick={() => setData("current_home_type", type)}
          >
            {type.charAt(0).toUpperCase() + type.slice(1)}
          </button>
        ))}
      </div>
      {errors.current_home_type && <p>{errors.current_home_type}</p>}

      {/* Floor Selection */}
      <label>Floor</label>
      <select value={data.floor} onChange={(e) => setData("floor", e.target.value)}>
        {["Basement", "Ground floor", "Mezzanine floor", "1", "2", "3", "4", "5+"].map((floor) => (
          <option key={floor} value={floor}>{floor}</option>
        ))}
      </select>
      {errors.floor && <p>{errors.floor}</p>}

      {/* Size Details */}
      <label>Rooms</label>
      <input type="number" value={data.rooms} onChange={(e) => setData("rooms", e.target.value)} />
      {errors.rooms && <p>{errors.rooms}</p>}

      <label>Square Meters</label>
      <input type="number" value={data.square_meters} onChange={(e) => setData("square_meters", e.target.value)} />
      {errors.square_meters && <p>{errors.square_meters}</p>}

      {/* Accessibility */}
      <label>Accessibility</label>
      <div>
        <label>
          <input type="checkbox" checked={data.accessibility_long_distance} onChange={(e) => setData("accessibility_long_distance", e.target.checked)} />
          Is longer than 5 meters
        </label>
        {data.accessibility_long_distance && (
          <input type="number" value={data.accessibility_distance_meters} onChange={(e) => setData("accessibility_distance_meters", e.target.value)} />
        )}

        <label>
          <input type="checkbox" checked={data.accessibility_has_steps} onChange={(e) => setData("accessibility_has_steps", e.target.checked)} />
          Has steps
        </label>
        {data.accessibility_has_steps && (
          <input type="number" value={data.accessibility_steps} onChange={(e) => setData("accessibility_steps", e.target.value)} />
        )}

        <label>
          <input type="checkbox" checked={data.accessibility_impeded} onChange={(e) => setData("accessibility_impeded", e.target.checked)} />
          Is otherwise impeded (e.g., maisonette)
        </label>
        {data.accessibility_impeded && (
          <textarea value={data.accessibility_notes} onChange={(e) => setData("accessibility_notes", e.target.value)} />
        )}
      </div>

      <button type="submit" disabled={processing}>Continue</button>
    </form>
  );
}
